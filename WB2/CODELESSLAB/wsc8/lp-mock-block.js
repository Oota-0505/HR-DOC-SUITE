(function (blocks, element, blockEditor, components, i18n) {
  const el = element.createElement;
  const __ = i18n.__;
  const useState = element.useState;
  const useEffect = element.useEffect;

  const useBlockProps = blockEditor.useBlockProps;
  const BlockControls = blockEditor.BlockControls;

  const MediaUpload = blockEditor.MediaUpload;
  const MediaUploadCheck = blockEditor.MediaUploadCheck;

  const ToolbarGroup = components.ToolbarGroup;
  const ToolbarButton = components.ToolbarButton;
  const PanelBody = components.PanelBody;
  const TextareaControl = components.TextareaControl;
  const TextControl = components.TextControl;
  const Button = components.Button;
  const Notice = components.Notice;

  // ---------- helpers ----------
  function cssEscapeSafe(str) {
    try {
      if (window.CSS && typeof window.CSS.escape === 'function') return window.CSS.escape(str);
    } catch (e) {}
    return String(str).replace(/[^a-zA-Z0-9_-]/g, '\\$&');
  }

function sanitizePreview(html) {
  if (!html) return '';
  // preview only (frontend is sanitized by PHP)
  html = html.replace(/<script\b[^>]*>[\s\S]*?<\/script>/gi, '');
  html = html.replace(/\son\w+="[^"]*"/gi, '');
  html = html.replace(/\son\w+='[^']*'/gi, '');
  html = html.replace(/\son\w+=\S+/gi, '');

  // 追加：href / xlink:href は完全禁止（要件）
  html = html.replace(/\s(?:href|xlink:href)\s*=\s*(".*?"|'.*?'|[^\s>]+)/gi, '');

  // 任意：foreignObject も落としておく（SVG経由の混入対策）
  html = html.replace(/<\s*foreignObject\b[^>]*>[\s\S]*?<\/\s*foreignObject\s*>/gi, '');

  return html;
}


  // Prevent <p> nesting accidents (from previous RichText behavior or pasted HTML)
  function normalizeEditableHtml(html) {
    if (!html) return '';

    // unwrap single <p>...</p>
    const onlyP = html.match(/^\s*<p[^>]*>([\s\S]*?)<\/p>\s*$/i);
    if (onlyP) html = onlyP[1];

    // join paragraphs with <br>
    html = html.replace(/<\/p>\s*<p[^>]*>/gi, '<br>');

    // remove remaining p tags
    html = html.replace(/<\/?p[^>]*>/gi, '');

    // normalize excessive breaks
    html = html.replace(/(<br>\s*){3,}/gi, '<br><br>');

    return html.trim();
  }

  function parseHtmlToDoc(html) {
    const parser = new DOMParser();
    return parser.parseFromString(html || '', 'text/html');
  }

  function isEditableTextEl(node) {
    if (!node || node.nodeType !== 1) return false;
    if (!node.classList || !node.classList.contains('editable')) return false;
    const tag = node.tagName.toLowerCase();
    return ['p', 'span', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'a', 'li'].includes(tag);
  }

  // Rule 1: img.editable only
  function isEditableImgEl(node) {
    if (!node || node.nodeType !== 1) return false;
    return node.tagName.toLowerCase() === 'img' && node.classList && node.classList.contains('editable');
  }

  // Assign keys by DOM order and extract editable maps
  function ensureKeysAndExtract(html) {
    const doc = parseHtmlToDoc(html);
    const root = doc.body;

    let tCount = 0, aCount = 0, iCount = 0;

    const textList = [];
    const linkList = [];
    const imgList = [];

    const all = root.querySelectorAll('*');
    all.forEach((node) => {
      // images
      if (isEditableImgEl(node)) {
        iCount += 1;
        const key = node.getAttribute('data-edit-img') || `i${iCount}`;
        node.setAttribute('data-edit-img', key);

        const src = node.getAttribute('src') || '';
        const alt = node.getAttribute('alt') || '';

        imgList.push({ key, id: 0, url: src, alt });
        return;
      }

      // text / links
      if (isEditableTextEl(node)) {
        const tag = node.tagName.toLowerCase();

        // a href
        if (tag === 'a') {
          aCount += 1;
          const aKey = node.getAttribute('data-edit-href') || `a${aCount}`;
          node.setAttribute('data-edit-href', aKey);

          const href = node.getAttribute('href') || '';
          linkList.push({ key: aKey, href });
        }

        // text key
        tCount += 1;
        const tKey = node.getAttribute('data-edit-key') || `t${tCount}`;
        node.setAttribute('data-edit-key', tKey);

        textList.push({
          key: tKey,
          tag,
          value: normalizeEditableHtml(node.innerHTML || ''),
        });
      }
    });

    const keyedHtml = root.innerHTML;
    return { keyedHtml, textList, linkList, imgList };
  }

  // Apply replacements to keyed html for preview/export
  function applyReplacementsToKeyedHtml(keyedHtml, textList, linkList, imgList) {
    const doc = parseHtmlToDoc(keyedHtml);
    const root = doc.body;

    (textList || []).forEach((t) => {
      const key = t && t.key ? t.key : '';
      if (!key) return;
      const elx = root.querySelector(`[data-edit-key="${cssEscapeSafe(key)}"]`);
      if (!elx) return;
      elx.innerHTML = normalizeEditableHtml(t.value || '');
    });

    (linkList || []).forEach((l) => {
      const key = l && l.key ? l.key : '';
      if (!key) return;
      const a = root.querySelector(`a[data-edit-href="${cssEscapeSafe(key)}"]`);
      if (!a) return;
      if (typeof l.href === 'string') a.setAttribute('href', l.href);
    });

    (imgList || []).forEach((im) => {
      const key = im && im.key ? im.key : '';
      if (!key) return;
      const img = root.querySelector(`img[data-edit-img="${cssEscapeSafe(key)}"]`);
      if (!img) return;
      if (im.url) img.setAttribute('src', im.url);
      img.setAttribute('alt', im.alt || '');
    });

    return root.innerHTML;
  }

  blocks.registerBlockType('lp/mock', {
    title: __('LP Mock (Free HTML + Editable)', 'lp-mock'),
    icon: 'layout',
    category: 'widgets',

    attributes: {
      advancedHtml: { type: 'string', default: '' },
      keyedHtml: { type: 'string', default: '' },

      editableText: { type: 'array', default: [] },
      editableLinks: { type: 'array', default: [] },
      editableImages: { type: 'array', default: [] },

      // A案：Visual編集結果を反映した完成HTML（コピー用）
      exportedHtml: { type: 'string', default: '' },
    },

    edit: function (props) {
      const { attributes, setAttributes } = props;
      const blockProps = useBlockProps({ className: 'lp-mock-block' });

      const [mode, setMode] = useState('visual'); // visual | code

      const advanced = (attributes.advancedHtml || '').trim();
      const hasAdvanced = advanced.length > 0;

      // 1) advancedHtmlからキー付与＋抽出
      useEffect(() => {
        if (!hasAdvanced) {
          setAttributes({
            keyedHtml: '',
            editableText: [],
            editableLinks: [],
            editableImages: [],
            exportedHtml: '',
          });
          return;
        }

        const extracted = ensureKeysAndExtract(advanced);

        // Merge with previous edited values by key (preserve user edits)
        const prevText = Array.isArray(attributes.editableText) ? attributes.editableText : [];
        const prevLinks = Array.isArray(attributes.editableLinks) ? attributes.editableLinks : [];
        const prevImgs = Array.isArray(attributes.editableImages) ? attributes.editableImages : [];

        const textMap = new Map(prevText.map((x) => [x.key, x]));
        const linkMap = new Map(prevLinks.map((x) => [x.key, x]));
        const imgMap = new Map(prevImgs.map((x) => [x.key, x]));

        const mergedText = extracted.textList.map((t) => {
          const prev = textMap.get(t.key);
          return prev ? { ...t, value: normalizeEditableHtml(prev.value) } : t;
        });

        const mergedLinks = extracted.linkList.map((l) => {
          const prev = linkMap.get(l.key);
          return prev ? { ...l, href: prev.href } : l;
        });

        const mergedImgs = extracted.imgList.map((im) => {
          const prev = imgMap.get(im.key);
          return prev
            ? { ...im, id: prev.id || 0, url: prev.url || im.url, alt: (prev.alt ?? im.alt) || '' }
            : im;
        });

        // exportedHtmlは別のuseEffectで同期するが、初回だけここで空を防ぐ
        setAttributes({
          keyedHtml: extracted.keyedHtml,
          editableText: mergedText,
          editableLinks: mergedLinks,
          editableImages: mergedImgs,
        });
        // eslint-disable-next-line react-hooks/exhaustive-deps
      }, [attributes.advancedHtml]);

      // 2) Visual編集結果 → exportedHtml（完成HTML）を常時同期
      useEffect(() => {
        if (!hasAdvanced) {
          if (attributes.exportedHtml) setAttributes({ exportedHtml: '' });
          return;
        }
        try {
          const base = (attributes.keyedHtml || '').trim()
            ? attributes.keyedHtml
            : attributes.advancedHtml;

          const applied = applyReplacementsToKeyedHtml(
            base,
            attributes.editableText || [],
            attributes.editableLinks || [],
            attributes.editableImages || []
          );

          if ((attributes.exportedHtml || '') !== applied) {
            setAttributes({ exportedHtml: applied });
          }
        } catch (e) {}
        // eslint-disable-next-line react-hooks/exhaustive-deps
      }, [
        attributes.advancedHtml,
        attributes.keyedHtml,
        JSON.stringify(attributes.editableText || []),
        JSON.stringify(attributes.editableLinks || []),
        JSON.stringify(attributes.editableImages || []),
      ]);

      // update helpers
      const updateText = (key, value) => {
        const list = Array.isArray(attributes.editableText) ? attributes.editableText.slice() : [];
        const idx = list.findIndex((x) => x.key === key);
        if (idx >= 0) {
          list[idx] = { ...list[idx], value: normalizeEditableHtml(value) };
          setAttributes({ editableText: list });
        }
      };

      const updateHref = (key, href) => {
        const list = Array.isArray(attributes.editableLinks) ? attributes.editableLinks.slice() : [];
        const idx = list.findIndex((x) => x.key === key);
        if (idx >= 0) {
          list[idx] = { ...list[idx], href };
          setAttributes({ editableLinks: list });
        }
      };

      const updateImage = (key, media) => {
        const list = Array.isArray(attributes.editableImages) ? attributes.editableImages.slice() : [];
        const idx = list.findIndex((x) => x.key === key);
        if (idx >= 0) {
          list[idx] = {
            ...list[idx],
            id: media.id || 0,
            url: media.url || '',
            alt: media.alt || list[idx].alt || '',
          };
          setAttributes({ editableImages: list });
        }
      };

      const clearAll = () => {
        setAttributes({
          advancedHtml: '',
          keyedHtml: '',
          editableText: [],
          editableLinks: [],
          editableImages: [],
          exportedHtml: '',
        });
      };

      // preview build
      const previewHtml = sanitizePreview(attributes.exportedHtml || '');

      const textList = Array.isArray(attributes.editableText) ? attributes.editableText : [];
      const linkList = Array.isArray(attributes.editableLinks) ? attributes.editableLinks : [];
      const imgList = Array.isArray(attributes.editableImages) ? attributes.editableImages : [];

      return el('div', blockProps, [
        el(BlockControls, { key: 'controls' },
          el(ToolbarGroup, {}, [
            el(ToolbarButton, {
              key: 'visual',
              isPressed: mode === 'visual',
              onClick: () => setMode('visual'),
            }, __('Visual', 'lp-mock')),
            el(ToolbarButton, {
              key: 'code',
              isPressed: mode === 'code',
              onClick: () => setMode('code'),
            }, __('Code', 'lp-mock')),
          ])
        ),

        mode === 'visual'
          ? el('div', { key: 'visualWrap' }, [
              !hasAdvanced
                ? el(Notice, { status: 'warning', isDismissible: false, key: 'noAdv' },
                    __('Advanced HTML is empty. Please input HTML in Code tab.', 'lp-mock')
                  )
                : null,

              hasAdvanced
                ? el('div', {
                    key: 'preview',
                    className: 'lp-mock-preview',
                    dangerouslySetInnerHTML: { __html: previewHtml },
                  })
                : null,

              hasAdvanced
                ? el(PanelBody, { title: __('Editable Fields (.editable)', 'lp-mock'), initialOpen: true, key: 'fields' }, [

                    // Text
                    textList.length
                      ? el('div', { key: 'texts' }, [
                          el('p', { style: { margin: '8px 0', opacity: 0.8 } }, __('Text', 'lp-mock')),
                          ...textList.map((t) =>
                            el('div', {
                              key: t.key,
                              style: {
                                padding: '8px',
                                border: '1px solid rgba(0,0,0,.08)',
                                marginBottom: '8px',
                                borderRadius: '8px'
                              }
                            }, [
                              el('div', { style: { fontSize: '12px', opacity: 0.7, marginBottom: '6px' } }, `${t.tag} / ${t.key}`),
                              el(TextareaControl, {
                                label: __('Content (br/span only)', 'lp-mock'),
                                value: t.value || '',
                                onChange: (v) => updateText(t.key, v),
                                rows: 2,
                                help: __('Use <br> for line breaks. You may use <span class="..."> inline.', 'lp-mock'),
                              }),
                            ])
                          ),
                        ])
                      : el(Notice, { status: 'info', isDismissible: false, key: 'noText' },
                          __('No editable text found. Add class="editable" to h/p/span/a/li elements.', 'lp-mock')
                        ),

                    // Links
                    linkList.length
                      ? el('div', { key: 'links', style: { marginTop: '12px' } }, [
                          el('p', { style: { margin: '8px 0', opacity: 0.8 } }, __('Links (a.editable)', 'lp-mock')),
                          ...linkList.map((l) =>
                            el(TextControl, {
                              key: l.key,
                              label: `${l.key} href`,
                              value: l.href || '',
                              onChange: (v) => updateHref(l.key, v),
                              placeholder: 'https://... or #anchor',
                            })
                          ),
                        ])
                      : null,

                    // Images
                    imgList.length
                      ? el('div', { key: 'imgs', style: { marginTop: '12px' } }, [
                          el('p', { style: { margin: '8px 0', opacity: 0.8 } }, __('Images (img.editable)', 'lp-mock')),
                          ...imgList.map((im) =>
                            el('div', {
                              key: im.key,
                              style: {
                                padding: '8px',
                                border: '1px solid rgba(0,0,0,.08)',
                                marginBottom: '8px',
                                borderRadius: '8px'
                              }
                            }, [
                              el('div', { style: { fontSize: '12px', opacity: 0.7, marginBottom: '6px' } }, `${im.key}`),
                              im.url ? el('img', {
                                src: im.url,
                                alt: im.alt || '',
                                style: { maxWidth: '100%', height: 'auto', display: 'block', marginBottom: '8px' }
                              }) : null,
                              el(MediaUploadCheck, {},
                                el(MediaUpload, {
                                  onSelect: (media) => updateImage(im.key, media),
                                  allowedTypes: ['image'],
                                  value: im.id || 0,
                                  render: ({ open }) => el(Button, { onClick: open, variant: 'secondary' }, __('Select / Change Image', 'lp-mock')),
                                })
                              ),
                              el(TextControl, {
                                label: __('alt', 'lp-mock'),
                                value: im.alt || '',
                                onChange: (v) => {
                                  const list = imgList.slice();
                                  const idx = list.findIndex((x) => x.key === im.key);
                                  if (idx >= 0) {
                                    list[idx] = { ...list[idx], alt: v };
                                    setAttributes({ editableImages: list });
                                  }
                                },
                              }),
                            ])
                          ),
                        ])
                      : el(Notice, { status: 'info', isDismissible: false, key: 'noImg' },
                          __('No editable images found. Add class="editable" to <img>.', 'lp-mock')
                        ),
                  ])
                : null,

              hasAdvanced
                ? el(Button, {
                    key: 'clearAll',
                    variant: 'secondary',
                    onClick: clearAll,
                    style: { marginTop: '12px' }
                  }, __('Clear Advanced HTML (and fields)', 'lp-mock'))
                : null,
            ])
          : el('div', { key: 'codeWrap' }, [
              // Advanced HTML (template)
              el(PanelBody, { title: __('Advanced HTML (Coder / Template)', 'lp-mock'), initialOpen: true, key: 'codePanel' }, [
                el(TextareaControl, {
                  label: __('HTML', 'lp-mock'),
                  value: attributes.advancedHtml,
                  onChange: (v) => setAttributes({ advancedHtml: v }),
                  help: __('Add class="editable" to text elements and <img> you want editable in Visual.', 'lp-mock'),
                  rows: 12,
                }),
                el(Button, {
                  variant: 'secondary',
                  onClick: clearAll,
                  disabled: !hasAdvanced,
                }, __('Clear Advanced HTML', 'lp-mock')),
              ]),

              // Exported HTML (synced)
              hasAdvanced
                ? el(PanelBody, { title: __('Exported HTML (Synced / Copy only)', 'lp-mock'), initialOpen: true, key: 'exportPanel' }, [
                    el(TextareaControl, {
                      label: __('HTML (Visual edits applied)', 'lp-mock'),
                      value: attributes.exportedHtml || '',
                      onChange: () => {},
                      disabled: true,
                      help: __('This is generated from the template + Visual edits. Copy & paste if needed.', 'lp-mock'),
                      rows: 12,
                    }),
                  ])
                : null,

              // Preview in Code mode as well
              hasAdvanced
                ? el('div', {
                    key: 'codePreview',
                    className: 'lp-mock-preview',
                    dangerouslySetInnerHTML: { __html: previewHtml },
                  })
                : el(Notice, { status: 'info', isDismissible: false, key: 'noAdv2' },
                    __('Advanced HTML is empty.', 'lp-mock')
                  ),
            ]),
      ]);
    },

    save: function () {
      return null;
    },
  });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components, window.wp.i18n);
