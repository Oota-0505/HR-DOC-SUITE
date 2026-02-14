<style id="theme-inline-css">
body a{text-decoration:none;}
.tac {text-align: center;}

:root {
  --c1: #3B82F6; /*標準*/
  --c2: #2169DE; /*濃い*/

	--fc: #0f172a; /*濃い*/
}

/* HERO CTA: お問い合わせボタンのみ表示 */
.kv .btnbox a:not([href*="#form"]) { display: none !important; }
/* 会社概要の上にあるCTAボタン：非表示 */
.cta .btnbox { display: none !important; }

@media (max-width: 999px){
body{margin:0; font-family:"Noto Sans JP",system-ui,-apple-system,"Segoe UI","Hiragino Kaku Gothic ProN","Noto Sans JP","Yu Gothic",sans-serif; width:100%; overflow-x:hidden;color:#0f172a; font-size:16px; line-height:1.2;}

#spMenu { position: fixed; z-index: 20; top: 3.5vw; right: 4.1vw; display: flex; justify-content: center; align-items: center; width: 6.5vw; height: 3.5vw; transition: all 1s; padding: 0; align-items: center; margin: 0; }
#spMenu .menu-trigger { position: relative; width: 6.5vw; height: 3.5vw; display: block; justify-content: center; align-items: center; margin: auto; top: 0; bottom: 0; left: 0; right: 0; }
.menu-trigger,.menu-trigger p { display: block; transition: all .4s; box-sizing: border-box; }
.menu-trigger.down p { position: absolute; left: 0; width: 100%; height: 1px; background: #000; font-size: 0.1em; }
.menu-trigger p { position: absolute; left: 0; width: 100%; height: 1px; background: #000; font-size: 0.1em; box-shadow: 0px 0px 8px rgba(0,0,0,0.2); }
.menu-trigger p:nth-of-type(1) { top: 1.5vw; }
.menu-trigger p:nth-of-type(2) { top: 3vw; }
.menu-trigger.active p:nth-of-type(1) { transform: translateY(1.2vw) rotate(-45deg); background: #fff; }
.menu-trigger.active p:nth-of-type(2) { transform: translateY(-.05vw) rotate(45deg); background: #fff; }

.spnavi { display: block; position: fixed; top: 0; left: 0; z-index: -21; opacity: 0; background: rgba(0,0,0,.9); width: 100%; height: 100vh; padding-top: 20vw; -webkit-box-sizing: border-box; box-sizing: border-box; overflow: hidden; -webkit-transition: all .3s; transition: all .3s; }
.spnavi.active { opacity: 1; z-index: 19;}
.spnavi a {position: relative; display: block; width: 100%; color: #fff; margin: 6vw auto 0; text-align: center;filter: blur(1.5rem); font-size: 130%; transition: all .5s;}
.spnavi.active a {filter: blur(0); transition: all .5s; margin-top: 4vw;}
.spnavi br {display: none;}
.spnavi .cl-nav-label-en {font-size: 70%;}


.bg{position:fixed; z-index:1; top:0; left:0; width:100%; height:100%;}
.bg img{width:100%; height:100%; object-fit:cover; object-position:top;}

/* ヘッダー全体 */
.cl-header{position:sticky; top:0; z-index:10; background:transparent;}
.cl-header-inner{max-width:1292px; margin:0 auto; padding:16px; display:flex; align-items:center; justify-content:space-between;}
/* ロゴ部分 */
.cl-logo{display:flex; align-items:center; gap:12px;}
.cl-logo-mark{width:50px; height:50px;}
.cl-logo-mark svg{width:100%; height:100%; object-fit:contain;}
.cl-logo-text-main{font-weight:700; color:#0f172a; font-weight:bold;}
.cl-logo-text-sub{ color:#64748b; font-weight: bold;}
/* ナビゲーション */
.cl-nav{display: none;}

main{position:relative; z-index:2; padding-bottom: 160px; overflow:hidden;}

main ul{width:100%; display:flex; justify-content:flex-start; flex-wrap:wrap; gap:8px; padding:0; margin:0 0 48px;}
main li{position:relative; padding-left:30px; list-style-type:none; height:24px; display:block; display:flex; justify-content:flex-start; align-items:center; font-weight:bold; font-size: 97%;}
main li::before{content:""; position:absolute; left:0; top:0.15em; /* 行の中心にしたいなら 50% + translateY を使う（下に例） */ width:24px; /* ←アイコン表示サイズ */ height:24px; /* ←アイコン表示サイズ */ background-repeat:no-repeat; background-position:center; background-size:contain; background-image:url("data:image/svg+xml,%3Csvg%20width='38'%20height='38'%20viewBox='0%200%2038%2038'%20fill='none'%20xmlns='http://www.w3.org/2000/svg'%3E%3Cg%20filter='url(%23filter0_d_1_121)'%3E%3Ccircle%20cx='17'%20cy='15'%20r='15'%20fill='white'/%3E%3C/g%3E%3Cg%20filter='url(%23filter1_i_1_121)'%3E%3Ccircle%20cx='17'%20cy='15'%20r='13'%20fill='%23FAFAFA'/%3E%3C/g%3E%3Cpath%20d='M19.7332%2011.7311L15.2605%2016.3027L13.9641%2014.9775C13.4804%2014.4832%2012.695%2014.4832%2012.2114%2014.9775C11.7277%2015.4719%2011.7277%2016.2732%2012.2114%2016.769L14.3842%2018.9899C14.6267%2019.2378%2014.9429%2019.3603%2015.2605%2019.3603C15.5781%2019.3603%2015.8943%2019.2363%2016.1369%2018.9899L21.4859%2013.5225C21.9695%2013.0282%2021.9695%2012.2269%2021.4859%2011.7311C21.0022%2011.2367%2020.2169%2011.2367%2019.7332%2011.7311Z'%20fill='%233B82F6'/%3E%3Cdefs%3E%3Cfilter%20id='filter0_d_1_121'%20x='0'%20y='0'%20width='38'%20height='38'%20filterUnits='userSpaceOnUse'%20color-interpolation-filters='sRGB'%3E%3CfeFlood%20flood-opacity='0'%20result='BackgroundImageFix'/%3E%3CfeColorMatrix%20in='SourceAlpha'%20type='matrix'%20values='0%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200%20127%200'%20result='hardAlpha'/%3E%3CfeOffset%20dx='2'%20dy='4'/%3E%3CfeGaussianBlur%20stdDeviation='2'/%3E%3CfeComposite%20in2='hardAlpha'%20operator='out'/%3E%3CfeColorMatrix%20type='matrix'%20values='0%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200.07%200'/%3E%3CfeBlend%20mode='normal'%20in2='BackgroundImageFix'%20result='effect1_dropShadow_1_121'/%3E%3CfeBlend%20mode='normal'%20in='SourceGraphic'%20in2='effect1_dropShadow_1_121'%20result='shape'/%3E%3C/filter%3E%3Cfilter%20id='filter1_i_1_121'%20x='4'%20y='2'%20width='26'%20height='30'%20filterUnits='userSpaceOnUse'%20color-interpolation-filters='sRGB'%3E%3CfeFlood%20flood-opacity='0'%20result='BackgroundImageFix'/%3E%3CfeBlend%20mode='normal'%20in='SourceGraphic'%20in2='BackgroundImageFix'%20result='shape'/%3E%3CfeColorMatrix%20in='SourceAlpha'%20type='matrix'%20values='0%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200%20127%200'%20result='hardAlpha'/%3E%3CfeOffset%20dy='4'/%3E%3CfeGaussianBlur%20stdDeviation='2'/%3E%3CfeComposite%20in2='hardAlpha'%20operator='arithmetic'%20k2='-1'%20k3='1'/%3E%3CfeColorMatrix%20type='matrix'%20values='0%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200.12%200'/%3E%3CfeBlend%20mode='normal'%20in2='shape'%20result='effect1_innerShadow_1_121'/%3E%3C/filter%3E%3C/defs%3E%3C/svg%3E");}

main .btnbox a{display:block;width:fit-content; height:auto; text-align:center; border-radius:30px; padding:16px 32px; margin:0 32px 0 0; line-height:1;}
main .btnbox a:first-child{background:linear-gradient(180deg,#51C3D5 0%,#2B5AF4 100%); box-shadow:3px 3px 4px 0 rgba(0,0,0,0.15); color:#fff; font-weight:bold;}
main .btnbox a:last-child{background:linear-gradient(180deg,#FFF 0%,#E8E4E4 100%);box-shadow:3px 3px 4px 0 rgba(0,0,0,0.15); font-weight:bold; color:#0f172a;}

.kv{position:relative; width:90%; height:auto; margin:0 auto 0; display: flex; justify-content: center; align-items: center; flex-direction:column-reverse;}
.kv .txt{ width: 100%;}
.kv .txt h1{font-size:32px; font-weight:900; margin: 0; padding: 0;}
.kv .txt h1 br,.kv .txt p br {display: none;}
.kv .txt p{line-height:1.6;display:block; margin:24px 0 40px;}
.kv .txt ul { display: flex; justify-content: flex-start; flex-wrap: wrap; gap:16px;}
.kv .txt ul li:nth-child(odd) { width: 100%;}
.kv .txt ul li:nth-child(even) { width: 100%;}
.kv .txt .btnbox{width:100%; display:block; margin: auto;}
.kv .txt .btnbox a { width: 80%; margin: 0 auto 16px;}
.kv img{  display: block; width: 85vw; height: auto; margin: 0 auto 24px;}

section{width:90%; margin:0 auto 0; padding-top: 100px;}
section.subpagesec { width: 90%; margin: 50px auto 0; padding: 32px; box-sizing: border-box; background: rgba(255,255,255,.4);}
section span {color: #3B82F6;}
section .ttlbox{text-align:center; margin-bottom:32px;}
section .ttlbox h2{font-size:32px; font-weight:700; text-align:center; margin: 0 auto ;}
section .ttlbox p{ display: block; margin: 8px auto 0;}

section .tricell{width:100%; display:flex; justify-content:flex-start; flex-wrap:wrap; gap:16px;}
section .tricell .cell{display:flex; justify-content:center; align-items:center; flex-direction:column; width:100%; padding: 24px 0; box-sizing:border-box;  background:rgba(255,255,255,.7); border-radius:8px; backdrop-filter:blur(12px);}
section .tricell .cell h3{--icon-color:#ef6f6f; /* ← SVG の色 */ position:relative; padding-left:38px; /* アイコン分の余白（サイズに応じて調整） */ font-size:18px; margin-bottom: 24px;}
section .tricell .cell h3::before{content:""; position:absolute; left:0; top:50%; transform:translateY(-50%); width:30px; /* アイコンサイズ */ height:auto; aspect-ratio:20/18; /* viewBox(33x30)比率に近い値 */ background-color:var(--icon-color); -webkit-mask:url("data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%2033%2030'%3E%3Cpath%20d='m27.63%2C30H5.37c-1.94%2C0-3.68-1-4.64-2.68-.97-1.68-.97-3.68%2C0-5.36L11.86%2C2.68c.97-1.68%2C2.71-2.68%2C4.64-2.68s3.68%2C1%2C4.64%2C2.68l11.13%2C19.27c.97%2C1.68.97%2C3.68%2C0%2C5.36-.97%2C1.68-2.71%2C2.68-4.64%2C2.68ZM16.5%2C1.54c-1.38%2C0-2.62.71-3.31%2C1.91L2.06%2C22.73c-.69%2C1.2-.69%2C2.63%2C0%2C3.82.69%2C1.2%2C1.93%2C1.91%2C3.31%2C1.91h22.26c1.38%2C0%2C2.62-.71%2C3.31-1.91.69-1.2.69-2.63%2C0-3.82L19.81%2C3.45c-.69-1.2-1.93-1.91-3.31-1.91Zm13.45%2C21.27L19.22%2C4.23c-.57-.98-1.59-1.57-2.72-1.57s-2.15.59-2.72%2C1.57L3.05%2C22.81c-.57.98-.57%2C2.16%2C0%2C3.14.57.98%2C1.59%2C1.57%2C2.72%2C1.57h21.46c1.14%2C0%2C2.15-.59%2C2.72-1.57.57-.98.57-2.16%2C0-3.14Zm-15.4-12.79c0-1.07.87-1.94%2C1.94-1.94s1.94.87%2C1.94%2C1.94v6.31c0%2C1.07-.87%2C1.94-1.94%2C1.94s-1.94-.87-1.94-1.94v-6.31Zm1.94%2C13.98c-1.1%2C0-1.99-.89-1.99-1.99s.89-1.99%2C1.99-1.99%2C1.99.89%2C1.99%2C1.99-.89%2C1.99-1.99%2C1.99Z'/%3E%3C/svg%3E") no-repeat center / contain;mask:url("data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%2033%2030'%3E%3Cpath%20d='m27.63%2C30H5.37c-1.94%2C0-3.68-1-4.64-2.68-.97-1.68-.97-3.68%2C0-5.36L11.86%2C2.68c.97-1.68%2C2.71-2.68%2C4.64-2.68s3.68%2C1%2C4.64%2C2.68l11.13%2C19.27c.97%2C1.68.97%2C3.68%2C0%2C5.36-.97%2C1.68-2.71%2C2.68-4.64%2C2.68ZM16.5%2C1.54c-1.38%2C0-2.62.71-3.31%2C1.91L2.06%2C22.73c-.69%2C1.2-.69%2C2.63%2C0%2C3.82.69%2C1.2%2C1.93%2C1.91%2C3.31%2C1.91h22.26c1.38%2C0%2C2.62-.71%2C3.31-1.91.69-1.2.69-2.63%2C0-3.82L19.81%2C3.45c-.69-1.2-1.93-1.91-3.31-1.91Zm13.45%2C21.27L19.22%2C4.23c-.57-.98-1.59-1.57-2.72-1.57s-2.15.59-2.72%2C1.57L3.05%2C22.81c-.57.98-.57%2C2.16%2C0%2C3.14.57.98%2C1.59%2C1.57%2C2.72%2C1.57h21.46c1.14%2C0%2C2.15-.59%2C2.72-1.57.57-.98.57-2.16%2C0-3.14Zm-15.4-12.79c0-1.07.87-1.94%2C1.94-1.94s1.94.87%2C1.94%2C1.94v6.31c0%2C1.07-.87%2C1.94-1.94%2C1.94s-1.94-.87-1.94-1.94v-6.31Zm1.94%2C13.98c-1.1%2C0-1.99-.89-1.99-1.99s.89-1.99%2C1.99-1.99%2C1.99.89%2C1.99%2C1.99-.89%2C1.99-1.99%2C1.99Z'/%3E%3C/svg%3E") no-repeat center / contain;}
section .tricell .cell .img {width: 66px; height: auto; text-align: center;aspect-ratio: 1 / 1; display: block; max-width: none;}
section .tricell .cell .img img {width: 100%; height: 100%; object-fit:cover;}

section .quadcell{ width:100%; display:flex; justify-content:flex-start; flex-wrap:wrap; gap:16px;}
section .quadcell .cell{display:flex; justify-content:center; align-items:center; flex-direction:column; width:100%; box-sizing:border-box; padding: 40px 0; box-sizing:border-box;  background:rgba(255,255,255,.7); border-radius:8px; backdrop-filter:blur(12px);}
section .quadcell .cell h3{font-size:18px; margin-bottom: 24px; }
section .quadcell .cell .img {width: 45%; height: auto; text-align: center; margin-bottom: 24px;}
section .quadcell .cell .img img {width: 100%; height: 100%; object-fit:cover;}
section .quadcell .cell p{font-size:13px; text-align: center; }

section .voicecell{width:100%; display:flex; justify-content:flex-start; flex-wrap:wrap; gap:16px;}
section .voicecell .cell{position: relative; display:block; width:420px; box-sizing:border-box; padding: 32px; box-sizing:border-box;  background:rgba(255,255,255,.7); border-radius:8px; backdrop-filter:blur(12px);}
section .voicecell .cell .icon { width: 100%; display: flex; justify-content: flex-start; align-items: center; margin-bottom: 32px;}
section .voicecell .cell .icon svg { width: 15%; height: auto; margin-right: 16px;}
section .voicecell .cell .voice{position:relative; text-align: left; width: 100%; font-weight: bold; font-size: 18px; line-height: 1.7; padding: 16px; box-sizing: border-box;}
section .voicecell .cell .voice::before{content:"“";position:absolute;left:0;top:0;font-size:40px;line-height:1;color:#3B82F6;transform:translate(-20%,-35%);pointer-events:none;}
section .voicecell .cell .voice::after{content:"”";position:absolute;right:0;bottom:0;font-size:40px;line-height:1;color:#3B82F6;transform:translate(20%,35%);pointer-events:none;}

section .servicecell{width:100%; display:flex; justify-content:flex-start; flex-wrap:wrap; gap:16px;}
section .servicecell .cell{ display:block; width:420px; padding: 32px; box-sizing:border-box;  background:rgba(255,255,255,.7); border-radius:8px; backdrop-filter:blur(12px); line-height: 1.7;}
section .servicecell .cell .top{ display:flex; justify-content: space-between; align-items: center; flex-direction: row-reverse; }
section .servicecell .cell .top .img { width: 15%; }
section .servicecell .cell .top .img img {width: 100%; height: 100%; object-fit:cover;}
section .servicecell .cell .top .ttlp { width: 85%; font-size: 21px; margin: 0; font-weight: bold; color: #3B82F6; padding-left: 24px;}
section .servicecell .cell .top .ttlp span {font-size: 13px; display: block;}

section .stepcell{width:100%;display:flex;justify-content:flex-start; flex-direction: column; gap:8px;align-items:stretch;background:rgba(255,255,255,.7);border-radius:8px;backdrop-filter:blur(12px);text-align:center;padding:56px 32px; box-sizing:border-box;}
section .stepcell .cell{position:relative;flex:1;box-sizing:border-box;}
section .stepcell .cell span { display: block; border-radius: 8px; background:linear-gradient(180deg,#51C3D5 0%,#2B5AF4 100%); color: #fff; text-align: center; padding: 8px; margin-bottom: 8px;}
section .stepcell .cell .img { width: 30%; height: auto; margin: 0 auto ; }
section .stepcell .cell .img img {width: 100%; height: 100%; object-fit:cover;}
section .stepcell .cell p.secttl {color: var(--c1); font-size: 23px; font-weight: bold;}
section .stepcell .cell p.btm {text-align: left;}

section .pricecell{width:100%; display:flex; justify-content:flex-start; flex-direction: column; margin-bottom: 48px;}
section .pricecell .cell{position: relative; display:flex; justify-content:flex-start; align-items:center;flex-direction: column; flex-wrap:wrap; width:100%; padding: 24px; box-sizing:border-box;  background:rgba(255,255,255,.7); border-radius:8px; backdrop-filter:blur(12px); margin-bottom: 16px; box-sizing: border-box;}
section .pricecell .cell .plangrade { position: relative; display: block; width: 100%; border-radius: 8px; background:#51C3D5; color: #fff; text-align: left; padding: 16px 24px 16px 72px; font-size: 16px; margin-bottom: 16px; box-sizing: border-box;}
section .pricecell .cell .plangrade img {position: absolute; z-index: 2; left: 15px; top: 20px; width: 46px; height: auto;}
section .pricecell .cell .plangrade span {display: block; font-size: 28px; color: #fff; margin-bottom: 1px; }
section .pricecell .cell:nth-of-type(1) .plangrade { background:#3B82F6;}
section .pricecell .cell:nth-of-type(2) .plangrade { background:#1C72FF;}
section .pricecell .cell:nth-of-type(3) .plangrade { background:#0357E0;}
section .pricecell .cell .price {font-size: 64px; display: block; width: 100%; font-weight: 700; text-align: center; margin: 0 auto 16px; padding-bottom: 24px; border-bottom: 1px dotted #94A3B8;}
section .pricecell .cell .price .lrg { display: inline; font-size: 21px; font-weight: 900; padding-left: 2px;}
section .pricecell .cell .price .sml {font-size: 16px; display: block; font-weight: 500; margin-top: 4px;}
section .pricecell .cell .price span {color: #0f172a!important;}
section .pricecell .cell ul { display: block; width: 100%; margin-bottom: 0; border-bottom: 1px dotted #94A3B8; padding-bottom: 16px; margin-bottom: 0; }
section .pricecell .cell ul li {margin-bottom: 8px; width: 100%;}
section .pricecell .cell ul li:last-child {margin-bottom: 0;}
section .pricecell .cell .btnbox{width:100%; display:flex; justify-content:center;}
section .pricecell .cell .btnbox a{display:block;width:fit-content; height:51px; text-align:center; border-radius:30px; padding:16px 32px; margin:0 auto; line-height:1;}
section .pricecell .cell .btnbox a{background:linear-gradient(180deg,#FFF 0%,#E8E4E4 100%);box-shadow:3px 3px 4px 0 rgba(0,0,0,0.15); font-weight:500; color:#0f172a;}

section .opcionbox{width:100%;display:block; text-align:center; box-sizing:border-box; margin-bottom: 80px;}
section .opcionbox h3 {width: 100%; height: auto; padding: 16px 0; margin: 0 auto 16px; font-size: 28px; }
section .opcionbox .box { display: flex; justify-content: space-between; flex-wrap: wrap; margin-bottom: 16px;}
section .opcionbox .box .cell {position: relative; width: 48%; text-align: center; list-style-type:none; font-weight:bold; box-sizing:border-box; padding: 24px; box-sizing:border-box;  background:rgba(255,255,255,.7); border-radius:8px; margin-bottom: 16px;}
section .opcionbox .box .cell .icon img { display: block; width: 22%; height: auto; margin: 0 auto 16px; }
section .opcionbox .box .cell .opname {color: var(--c1); display: block; margin: 16px auto 2px;}

section .opcionbox .box .cell .price {display: flex; justify-content: center; align-items: flex-end;font-size: 23px; margin: 16px auto 1px;}
section .opcionbox .box .cell .price span { color: var(--fc1); display: inline; margin: 0 ; font-size: 12px; font-weight: normal;}
section .opcionbox .box .cell .price2 {display: flex; justify-content: center; align-items: flex-end; font-size: 14px; margin: 0 auto;}
section .opcionbox .box .cell .price2 span { color: var(--fc1); display: inline; margin: 0 ; font-size: 10px;}




.cta .btnbox {width: 100%; margin: 0 auto; display:flex; justify-content: center; align-items:center; flex-direction: column;}
.cta .btnbox a { width: 80%; margin: 0 auto 16px;}


section .company{width:100%;margin: 0 auto;display:block;background:rgba(255,255,255,.7);border-radius:8px;backdrop-filter:blur(12px);text-align:left;padding:56px 32px; box-sizing:border-box;}
section .company .cell {width:100%; display:flex; justify-content:flex-start; margin-bottom: 16px; padding-bottom: 16px; border-bottom: dotted 1px #dadada;}
section .company .cell:nth-last-of-type(1) {margin-bottom: 0;}
section .company .cell:nth-of-type(1) { padding-top: 16px; border-top: dotted 1px #dadada;}
section .company .cell .cpttl {width:30%; text-align-last: left;}
section .company .cell .cpdd {width:70%; text-align-last: left;}
section .company .cell .cpdd p {padding: 0; margin: 0;}
#map iframe{height: 30vh;}

.pagettl {width: 100%; margin: 24px auto 0; text-align: center;}
.pagettl h1 {font-size: 25px;}
.box h2 {color: #3B82F6;}
.duocell {display: flex; justify-content: flex-start; align-items: center; margin-bottom: 16px;}
.duocell p:first-child {width: 30%;}
.duocell p:last-child {width: 70%;}
.subpagesec .box {margin-bottom: 24px;}
.subpagesec p { line-height: 1.6;}


footer{position:relative; z-index:2; overflow:hidden; background: #fff; text-align: center;}
footer .top { width: 100%; display: block; text-align: center; padding: 16px 0; box-sizing: border-box;}
footer .top a {display: block; margin: 0 auto 8px; color: #0f172a; }
footer .top a:hover {color: #3B82F6;}
footer .btm {padding: 8px; box-sizing: border-box; text-align: center; color: #fff; background: #3B82F6; font-size: 12px;}

input[type="text"],textarea{margin:0;padding:0;border:0;border-radius:0;background:none;font:inherit;color:inherit;outline:none;appearance:none;-webkit-appearance:none;}
#form { padding-top: 100px; margin-top: 0; overflow: hidden; width: 90%;}
#form form {}
#form form input,textarea { display: block; width: 100%; padding: 16px; box-sizing: border-box; background: #fff; border: none; border-radius: 8px; margin-bottom: 16px; font-size: 16px;}
#form form .ppagree{width: fit-content; margin: 16px auto;padding:16px;border:1px solid rgba(0,0,0,.12);border-radius:8px;background:rgba(255,255,255,1);}
#form form .ppagree__label{display:flex;align-items:center;gap:10px;cursor:pointer;user-select:none;}
#form form .ppagree__check{width:18px;height:18px;margin:0;accent-color:#3B82F6;}
#form form .ppagree__txt{line-height:1.5; color: #3B82F6;}
#form form .ppagree__note{margin:0 0 16px;font-size:12px;line-height:1.6;color:rgba(0,0,0,.65); text-align: center;}
#form form .ppagree__link{color:#3B82F6;text-decoration:underline;}
#form form .ppagree__link:hover{text-decoration:none;}
#form form button { position: relative; display: block; width: 80%; margin: 0 auto; padding: 16px 0 16px; background:linear-gradient(180deg,#51C3D5 0%,#2B5AF4 100%); box-shadow:3px 3px 4px 0 rgba(0,0,0,0.15); color:#fff; font-weight:bold; text-align: center; border-radius: 4px; transition: all .5s; border: none; font-size: 18px;}
#form form button span {  color: #fff; display: block; text-align: center;}
#form form button::after{content:"";position:absolute;top:40%;right:16px;width:10px;height:12px;background-repeat:no-repeat;background-position:center;background-size:contain;background-image:url("data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20width='11.832'%20height='13.658'%20viewBox='0%200%2011.832%2013.658'%3E%3Cpath%20d='M6.831%2C0l0%2C0%2C0%2C0L5.064%2C3.053l0%2C0L3.416%2C5.917%2C0%2C11.833H3.525l3.3-5.722%2C3.3%2C5.722h3.525L11.888%2C8.768l0%2C0L10.247%2C5.917l-.118-.205L8.594%2C3.053Z'%20transform='translate(11.833)%20rotate(90)'%20fill='%23fff'/%3E%3C/svg%3E");pointer-events:none;}
}





















/* =========================
   Tablet (1000px - 1291px)
   ========================= */
@media (min-width: 1000px) and (max-width: 1291px){
body{margin:0; font-family:"Noto Sans JP",system-ui,-apple-system,"Segoe UI","Hiragino Kaku Gothic ProN","Noto Sans JP","Yu Gothic",sans-serif; width:100%; overflow-x:hidden;color:#0f172a; font-size:14px; line-height:1.2;}
#spMenu,.spnavi {display: none;}
.bg{position:fixed; z-index:1; top:0; left:0; width:100%; height:100%;}
.bg img{width:100%; height:100%; object-fit:cover; object-position:top;}

/* ヘッダー全体 */
.cl-header{position:sticky; /* 必要なければ fixed / static に変えてOK */ top:0; z-index:100; background:linear-gradient(to right,#f2f7ff,#ffffff); backdrop-filter:blur(10px); border-bottom:1px solid rgba(148,163,184,0.25);}
.cl-header-inner{max-width:96%; margin:0 auto; padding:16px 0; display:flex; align-items:center; justify-content:space-between;}
/* ロゴ部分 */
.cl-logo{display:flex; align-items:center; gap:12px;}
.cl-logo-mark{width:50px; height:50px;}
.cl-logo-mark svg{width:100%; height:100%; object-fit:contain;}
.cl-logo-text-main{font-weight:700; font-size:21px; color:#0f172a; font-weight:bold;}
.cl-logo-text-sub{font-size:12px; color:#64748b;}
/* ナビゲーション */
.cl-nav{display:flex; align-items:center; gap:61px; font-size:13px;}
.cl-nav-item{text-align:center; line-height:1.3; cursor:pointer;}
.cl-nav-label-ja{color:#0f172a; font-weight:bold; font-size:14px; white-space:nowrap;}
.cl-nav-label-en{color:#94a3b8; font-size:10px; text-transform:uppercase;}
.cl-nav-item:hover .cl-nav-label-ja{color:#2563eb;}

main{position:relative; z-index:2; padding-bottom: 160px; overflow:hidden;}

main ul{width:55%; display:flex; justify-content:flex-start; flex-wrap:wrap; gap:8px 36px; padding:0; margin:0 0 36px;}
main li{position:relative; padding-left:40px; list-style-type:none; width:100%; height:36px; display:block; display:flex; justify-content:flex-start; align-items:center; font-weight:bold;}
main li::before{content:""; position:absolute; left:0; top:0.15em; /* 行の中心にしたいなら 50% + translateY を使う（下に例） */ width:36px; /* ←アイコン表示サイズ */ height:36px; /* ←アイコン表示サイズ */ background-repeat:no-repeat; background-position:center; background-size:contain; background-image:url("data:image/svg+xml,%3Csvg%20width='38'%20height='38'%20viewBox='0%200%2038%2038'%20fill='none'%20xmlns='http://www.w3.org/2000/svg'%3E%3Cg%20filter='url(%23filter0_d_1_121)'%3E%3Ccircle%20cx='17'%20cy='15'%20r='15'%20fill='white'/%3E%3C/g%3E%3Cg%20filter='url(%23filter1_i_1_121)'%3E%3Ccircle%20cx='17'%20cy='15'%20r='13'%20fill='%23FAFAFA'/%3E%3C/g%3E%3Cpath%20d='M19.7332%2011.7311L15.2605%2016.3027L13.9641%2014.9775C13.4804%2014.4832%2012.695%2014.4832%2012.2114%2014.9775C11.7277%2015.4719%2011.7277%2016.2732%2012.2114%2016.769L14.3842%2018.9899C14.6267%2019.2378%2014.9429%2019.3603%2015.2605%2019.3603C15.5781%2019.3603%2015.8943%2019.2363%2016.1369%2018.9899L21.4859%2013.5225C21.9695%2013.0282%2021.9695%2012.2269%2021.4859%2011.7311C21.0022%2011.2367%2020.2169%2011.2367%2019.7332%2011.7311Z'%20fill='%233B82F6'/%3E%3Cdefs%3E%3Cfilter%20id='filter0_d_1_121'%20x='0'%20y='0'%20width='38'%20height='38'%20filterUnits='userSpaceOnUse'%20color-interpolation-filters='sRGB'%3E%3CfeFlood%20flood-opacity='0'%20result='BackgroundImageFix'/%3E%3CfeColorMatrix%20in='SourceAlpha'%20type='matrix'%20values='0%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200%20127%200'%20result='hardAlpha'/%3E%3CfeOffset%20dx='2'%20dy='4'/%3E%3CfeGaussianBlur%20stdDeviation='2'/%3E%3CfeComposite%20in2='hardAlpha'%20operator='out'/%3E%3CfeColorMatrix%20type='matrix'%20values='0%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200.07%200'/%3E%3CfeBlend%20mode='normal'%20in2='BackgroundImageFix'%20result='effect1_dropShadow_1_121'/%3E%3CfeBlend%20mode='normal'%20in='SourceGraphic'%20in2='effect1_dropShadow_1_121'%20result='shape'/%3E%3C/filter%3E%3Cfilter%20id='filter1_i_1_121'%20x='4'%20y='2'%20width='26'%20height='30'%20filterUnits='userSpaceOnUse'%20color-interpolation-filters='sRGB'%3E%3CfeFlood%20flood-opacity='0'%20result='BackgroundImageFix'/%3E%3CfeBlend%20mode='normal'%20in='SourceGraphic'%20in2='BackgroundImageFix'%20result='shape'/%3E%3CfeColorMatrix%20in='SourceAlpha'%20type='matrix'%20values='0%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200%20127%200'%20result='hardAlpha'/%3E%3CfeOffset%20dy='4'/%3E%3CfeGaussianBlur%20stdDeviation='2'/%3E%3CfeComposite%20in2='hardAlpha'%20operator='arithmetic'%20k2='-1'%20k3='1'/%3E%3CfeColorMatrix%20type='matrix'%20values='0%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200.12%200'/%3E%3CfeBlend%20mode='normal'%20in2='shape'%20result='effect1_innerShadow_1_121'/%3E%3C/filter%3E%3C/defs%3E%3C/svg%3E");}

main .btnbox a{display:block;width:300px;  height:auto; text-align:center; border-radius:30px; padding:16px 32px; margin:0 32px 0 0; line-height:1;}
main .btnbox a:first-child{background:linear-gradient(180deg,#51C3D5 0%,#2B5AF4 100%); box-shadow:3px 3px 4px 0 rgba(0,0,0,0.15); color:#fff; font-weight:bold;}
main .btnbox a:last-child{background:linear-gradient(180deg,#FFF 0%,#E8E4E4 100%);box-shadow:3px 3px 4px 0 rgba(0,0,0,0.15); font-weight:bold; color:#0f172a;}

.kv{position:relative; width:1292px; height:auto; margin:0 auto;}
.kv .txt{padding:0 0 0 24px;}
.kv .txt h1{font-size:61px; font-weight:900; margin-bottom:36px; line-height:1.2;}
.kv .txt p{line-height:1.6;display:block; margin-bottom:36px;}

.kv .txt .btnbox{width:100%; display:flex; justify-content:flex-start;}
.kv img{position:absolute; z-index:2; left:40%; top:56px; width:50vw; height:auto;}

section{width:96%; margin:0 auto 0; padding-top: 160px;}
section.subpagesec { width: 1000px; margin: 100px auto 0; padding: 64px; box-sizing: border-box; background: rgba(255,255,255,.4);}
section span {color: #3B82F6;}
section .ttlbox{text-align:center; margin-bottom:56px;}
section .ttlbox h2{font-size:51px; font-weight:700; text-align:center; margin-bottom: 16px;}
section .ttlbox p{ display: block; margin: 0 auto 0;}

section .tricell{width:100%; display:flex; justify-content:flex-start; flex-wrap:wrap; gap:16px;}
section .tricell .cell{display:flex; justify-content:center; align-items:center; flex-direction:column; width:32%; padding: 20px 0; box-sizing:border-box;  background:rgba(255,255,255,.7); border-radius:8px; backdrop-filter:blur(12px);}
section .tricell .cell h3{--icon-color:#ef6f6f; /* ← SVG の色 */ position:relative; padding-left:38px; /* アイコン分の余白（サイズに応じて調整） */ font-size:18px; margin-bottom: 24px;}
section .tricell .cell h3::before{content:""; position:absolute; left:0; top:50%; transform:translateY(-50%); width:30px; /* アイコンサイズ */ height:auto; aspect-ratio:20/18; /* viewBox(33x30)比率に近い値 */ background-color:var(--icon-color); -webkit-mask:url("data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%2033%2030'%3E%3Cpath%20d='m27.63%2C30H5.37c-1.94%2C0-3.68-1-4.64-2.68-.97-1.68-.97-3.68%2C0-5.36L11.86%2C2.68c.97-1.68%2C2.71-2.68%2C4.64-2.68s3.68%2C1%2C4.64%2C2.68l11.13%2C19.27c.97%2C1.68.97%2C3.68%2C0%2C5.36-.97%2C1.68-2.71%2C2.68-4.64%2C2.68ZM16.5%2C1.54c-1.38%2C0-2.62.71-3.31%2C1.91L2.06%2C22.73c-.69%2C1.2-.69%2C2.63%2C0%2C3.82.69%2C1.2%2C1.93%2C1.91%2C3.31%2C1.91h22.26c1.38%2C0%2C2.62-.71%2C3.31-1.91.69-1.2.69-2.63%2C0-3.82L19.81%2C3.45c-.69-1.2-1.93-1.91-3.31-1.91Zm13.45%2C21.27L19.22%2C4.23c-.57-.98-1.59-1.57-2.72-1.57s-2.15.59-2.72%2C1.57L3.05%2C22.81c-.57.98-.57%2C2.16%2C0%2C3.14.57.98%2C1.59%2C1.57%2C2.72%2C1.57h21.46c1.14%2C0%2C2.15-.59%2C2.72-1.57.57-.98.57-2.16%2C0-3.14Zm-15.4-12.79c0-1.07.87-1.94%2C1.94-1.94s1.94.87%2C1.94%2C1.94v6.31c0%2C1.07-.87%2C1.94-1.94%2C1.94s-1.94-.87-1.94-1.94v-6.31Zm1.94%2C13.98c-1.1%2C0-1.99-.89-1.99-1.99s.89-1.99%2C1.99-1.99%2C1.99.89%2C1.99%2C1.99-.89%2C1.99-1.99%2C1.99Z'/%3E%3C/svg%3E") no-repeat center / contain;mask:url("data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%2033%2030'%3E%3Cpath%20d='m27.63%2C30H5.37c-1.94%2C0-3.68-1-4.64-2.68-.97-1.68-.97-3.68%2C0-5.36L11.86%2C2.68c.97-1.68%2C2.71-2.68%2C4.64-2.68s3.68%2C1%2C4.64%2C2.68l11.13%2C19.27c.97%2C1.68.97%2C3.68%2C0%2C5.36-.97%2C1.68-2.71%2C2.68-4.64%2C2.68ZM16.5%2C1.54c-1.38%2C0-2.62.71-3.31%2C1.91L2.06%2C22.73c-.69%2C1.2-.69%2C2.63%2C0%2C3.82.69%2C1.2%2C1.93%2C1.91%2C3.31%2C1.91h22.26c1.38%2C0%2C2.62-.71%2C3.31-1.91.69-1.2.69-2.63%2C0-3.82L19.81%2C3.45c-.69-1.2-1.93-1.91-3.31-1.91Zm13.45%2C21.27L19.22%2C4.23c-.57-.98-1.59-1.57-2.72-1.57s-2.15.59-2.72%2C1.57L3.05%2C22.81c-.57.98-.57%2C2.16%2C0%2C3.14.57.98%2C1.59%2C1.57%2C2.72%2C1.57h21.46c1.14%2C0%2C2.15-.59%2C2.72-1.57.57-.98.57-2.16%2C0-3.14Zm-15.4-12.79c0-1.07.87-1.94%2C1.94-1.94s1.94.87%2C1.94%2C1.94v6.31c0%2C1.07-.87%2C1.94-1.94%2C1.94s-1.94-.87-1.94-1.94v-6.31Zm1.94%2C13.98c-1.1%2C0-1.99-.89-1.99-1.99s.89-1.99%2C1.99-1.99%2C1.99.89%2C1.99%2C1.99-.89%2C1.99-1.99%2C1.99Z'/%3E%3C/svg%3E") no-repeat center / contain;}
section .tricell .cell .img {width: 66px; height: 66px; text-align: center;aspect-ratio: 1 / 1;}
section .tricell .cell .img img {width: 100%; height: 100%; object-fit:cover;}

section .quadcell{ width:100%; display:flex; justify-content:flex-start; flex-wrap:wrap; gap:16px;}
section .quadcell .cell{display:flex; justify-content:center; align-items:center; flex-direction:column; width:23%; box-sizing:border-box; padding: 20px; box-sizing:border-box;  background:rgba(255,255,255,.7); border-radius:8px; backdrop-filter:blur(12px);}
section .quadcell .cell h3{font-size:18px; margin-bottom: 24px; }
section .quadcell .cell .img {width: 45%; height: auto; text-align: center; margin-bottom: 24px;}
section .quadcell .cell .img img {width: 100%; height: 100%; object-fit:cover;}
section .quadcell .cell p{font-size:13px; text-align: center; }

section .voicecell{width:100%; display:flex; justify-content:flex-start; flex-wrap:wrap; gap:16px;}
section .voicecell .cell{position: relative; display:block; width:32%; box-sizing:border-box; padding: 20px; box-sizing:border-box;  background:rgba(255,255,255,.7); border-radius:8px; backdrop-filter:blur(12px);}
section .voicecell .cell .icon { width: 100%; display: flex; justify-content: flex-start; align-items: center; margin-bottom: 32px;}
section .voicecell .cell .icon svg { width: 15%; height: auto; margin-right: 16px;}
section .voicecell .cell .voice{position:relative; text-align: left; width: 100%; font-weight: bold; font-size: 18px; line-height: 1.7; padding: 16px; box-sizing: border-box;}
section .voicecell .cell .voice::before{content:"“";position:absolute;left:0;top:0;font-size:40px;line-height:1;color:#3B82F6;transform:translate(-20%,-35%);pointer-events:none;}
section .voicecell .cell .voice::after{content:"”";position:absolute;right:0;bottom:0;font-size:40px;line-height:1;color:#3B82F6;transform:translate(20%,35%);pointer-events:none;}

section .servicecell{width:100%; display:flex; justify-content:flex-start; flex-wrap:wrap; gap:16px;}
section .servicecell .cell{ display:block; width:32%; padding: 20px; box-sizing:border-box;  background:rgba(255,255,255,.7); border-radius:8px; backdrop-filter:blur(12px); line-height: 1.7;}
section .servicecell .cell .top{ display:flex; justify-content: space-between; align-items: center; flex-direction: row-reverse; }
section .servicecell .cell .top .img { width: 15%; }
section .servicecell .cell .top .img img {width: 100%; height: 100%; object-fit:cover;}
section .servicecell .cell .top .ttlp { width: 85%; font-size: 21px; margin: 0; font-weight: bold; color: #3B82F6; padding-left: 24px;}
section .servicecell .cell .top .ttlp span {font-size: 13px; display: block;}

section .stepcell{width:100%;display:flex;justify-content:flex-start;gap:8px;align-items:stretch;background:rgba(255,255,255,.7);border-radius:8px;backdrop-filter:blur(12px);text-align:center;padding:56px 32px; box-sizing:border-box;}
section .stepcell .cell{position:relative;flex:1;box-sizing:border-box;}
section .stepcell .cell:nth-child(-n+5){padding-right:56px;}
section .stepcell .cell:nth-child(-n+5)::after{content:"";position:absolute;right:10px;top:50%;transform:translateY(-50%);width:25px;height:25px;background-repeat:no-repeat;background-position:center;background-size:contain;background-image:url("data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%2010%2010'%3E%3Cpath%20d='M2%201L8%205L2%209Z'%20fill='%23000'/%3E%3C/svg%3E"); opacity: .2;}
section .stepcell .cell span { display: block; border-radius: 8px; background:linear-gradient(180deg,#51C3D5 0%,#2B5AF4 100%); color: #fff; text-align: center; padding: 8px; font-size: 12px; margin-bottom: 16px;}
section .stepcell .cell .img { width: 60%; height: auto; margin: 0 auto 16px; }
section .stepcell .cell .img img {width: 100%; height: 100%; object-fit:cover;}
section .stepcell .cell p.secttl {color: var(--c1); font-size: 16px; font-weight: bold;}
section .stepcell .cell p.btm {text-align: left; line-height: 1.7; font-size: 13px;}


section .pricecell{width:100%; display:flex; justify-content:flex-start; gap:16px; margin-bottom: 48px;}
section .pricecell .cell{position: relative; display:flex; justify-content:flex-start; align-items:center;flex-direction: column; flex-wrap:wrap; width:420px; padding: 32px; box-sizing:border-box;  background:rgba(255,255,255,.7); border-radius:8px; backdrop-filter:blur(12px);}
section .pricecell .cell .plangrade { position: relative; display: block; width: 100%; border-radius: 8px; background:#51C3D5; color: #fff; text-align: left; padding: 16px 24px 16px 72px; font-size: 16px; margin-bottom: 16px; box-sizing: border-box;}
section .pricecell .cell .plangrade img {position: absolute; z-index: 2; left: 15px; top: 20px; width: 46px; height: auto;}
section .pricecell .cell .plangrade span {display: block; font-size: 28px; color: #fff; margin-bottom: 1px; }
section .pricecell .cell:nth-of-type(1) .plangrade { background:#3B82F6;}
section .pricecell .cell:nth-of-type(2) .plangrade { background:#1C72FF;}
section .pricecell .cell:nth-of-type(3) .plangrade { background:#0357E0;}
section .pricecell .cell .price {font-size: 64px; display: block; width: 100%; font-weight: 700; text-align: center; margin: 0 auto 16px; padding-bottom: 24px; border-bottom: 1px dotted #94A3B8;}
section .pricecell .cell .price .lrg { display: inline; font-size: 21px; font-weight: normal; padding-left: 2px;}
section .pricecell .cell .price .sml {font-size: 16px; display: block; font-weight: 500; margin-top: 4px;}
section .pricecell .cell .price span {color: #0f172a!important;}
section .pricecell .cell ul { display: block; width: 100%; margin-bottom: 0; border-bottom: 1px dotted #94A3B8; padding-bottom: 16px; margin-bottom: 0; }
section .pricecell .cell ul li {margin-bottom: 8px; width: 100%;}
section .pricecell .cell ul li:last-child {margin-bottom: 0;}
section .pricecell .cell .btnbox{width:100%; display:flex; justify-content:center;}
section .pricecell .cell .btnbox a{display:block;width:fit-content; height:51px; text-align:center; border-radius:30px; padding:16px 32px; margin:0 auto; line-height:1;}
section .pricecell .cell .btnbox a{background:linear-gradient(180deg,#FFF 0%,#E8E4E4 100%);box-shadow:3px 3px 4px 0 rgba(0,0,0,0.15); font-weight:500; color:#0f172a;}

section .opcionbox{width:100%;display:block; text-align:center; box-sizing:border-box; margin-bottom: 80px;}
section .opcionbox h3 {width: 100%; height: auto; padding: 16px 0; margin: 0 auto 16px; font-size: 28px; }
section .opcionbox .box { flex:1; display:flex; justify-content:flex-start; flex-wrap:wrap; gap:16px;}
section .opcionbox .box .cell {position: relative; width: 18.8%; text-align: center; list-style-type:none; font-weight:bold; box-sizing:border-box; padding: 20px; box-sizing:border-box;  background:rgba(255,255,255,.7); border-radius:8px;}
section .opcionbox .box .cell .icon img { display: block; width: 32%; height: auto; margin: 0 auto 16px; }
section .opcionbox .box .cell .opname {color: var(--c1); display: block; margin: 16px auto 2px;}
section .opcionbox .box .cell .price {display: flex; justify-content: center; align-items: flex-end;font-size: 28px; margin: 16px auto 1px;}
section .opcionbox .box .cell .price span { color: var(--fc1); display: inline; margin: 0 ; font-size: 12px; font-weight: normal;}
section .opcionbox .box .cell .price2 {display: flex; justify-content: center; align-items: flex-end; font-size: 16px; margin: 0 auto;}
section .opcionbox .box .cell .price2 span { color: var(--fc1); display: inline; margin: 0 ; font-size: 10px;}

.cta .btnbox {width: fit-content; margin: 0 auto; display:flex; justify-content: center; align-items:center;}
.cta .btnbox a {width: 300px;}


section .company{width:var(--max);margin: 0 auto;display:block;background:rgba(255,255,255,.7);border-radius:8px;backdrop-filter:blur(12px);text-align:left;padding:56px 32px; box-sizing:border-box;}
section .company .cell {width:100%; display:flex; justify-content:flex-start; margin-bottom: 16px; padding-bottom: 16px; border-bottom: dotted 1px #dadada;}
section .company .cell:nth-last-of-type(1) {margin-bottom: 0;}
section .company .cell:nth-of-type(1) { padding-top: 16px; border-top: dotted 1px #dadada;}
section .company .cell .cpttl {width:20%; text-align-last: left;}
section .company .cell .cpdd {width:80%; text-align-last: left;}
section .company .cell p {margin: 0;}

section #cta{width:60%;margin: 0 auto;display:block;background:rgba(255,255,255,.7);border-radius:8px;backdrop-filter:blur(12px);text-align:left;padding:56px 32px; box-sizing:border-box;}
section #cta form .cell {width:100%; display:flex; justify-content:flex-start; margin-bottom: 16px;}

.pagettl {width: 100%; margin: 60px auto 0; text-align: center;}
.box h2 {color: #3B82F6;}
.duocell {display: flex; justify-content: flex-start; align-items: center; margin-bottom: 16px;}
.duocell p:first-child {width: 20%;}
.duocell p:last-child {width: 80%;}
.subpagesec .box {margin-bottom: 60px;}
.subpagesec p { line-height: 1.6;}


footer{position:relative; z-index:2; overflow:hidden; background: #fff; text-align: center;}
footer .top { width: 100%; display: flex; justify-content: center; align-items: center; padding: 8px; box-sizing: border-box;}
footer .top a {display: block; font-size: 13px; margin: 0 8px 8px; color: #0f172a; }
footer .top a:hover {color: #3B82F6;}
footer .btm {padding: 8px; box-sizing: border-box; text-align: center; color: #fff; background: #3B82F6;}

input[type="text"],textarea{margin:0;padding:0;border:0;border-radius:0;background:none;font:inherit;color:inherit;outline:none;appearance:none;-webkit-appearance:none;}
#form { padding-top: 100px; margin-top: 0; overflow: hidden; width: 60%;}
#form form {}
#form form input,textarea { display: block; width: 100%; padding: 16px; box-sizing: border-box; background: #fff; border: none; border-radius: 8px; margin-bottom: 16px; font-size: 16px;}
#form form .ppagree{width: fit-content; margin: 16px auto;padding:16px;border:1px solid rgba(0,0,0,.12);border-radius:8px;background:rgba(255,255,255,1);}
#form form .ppagree__label{display:flex;align-items:center;gap:10px;cursor:pointer;user-select:none;}
#form form .ppagree__check{width:18px;height:18px;margin:0;accent-color:#3B82F6;}
#form form .ppagree__txt{line-height:1.5; color: #3B82F6;}
#form form .ppagree__note{margin:0 0 16px;font-size:12px;line-height:1.6;color:rgba(0,0,0,.65); text-align: center;}
#form form .ppagree__link{color:#3B82F6;text-decoration:underline;}
#form form .ppagree__link:hover{text-decoration:none;}
#form form button { position: relative; display: block; width: 40%; margin: 0 auto; padding: 16px 0 16px; background:linear-gradient(180deg,#51C3D5 0%,#2B5AF4 100%); box-shadow:3px 3px 4px 0 rgba(0,0,0,0.15); color:#fff; font-weight:bold; text-align: center; border-radius: 4px; transition: all .5s; border: none; font-size: 18px;}
#form form button span {  color: #fff; display: block; text-align: center;}
#form form button::after{content:"";position:absolute;top:40%;right:16px;width:10px;height:12px;background-repeat:no-repeat;background-position:center;background-size:contain;background-image:url("data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20width='11.832'%20height='13.658'%20viewBox='0%200%2011.832%2013.658'%3E%3Cpath%20d='M6.831%2C0l0%2C0%2C0%2C0L5.064%2C3.053l0%2C0L3.416%2C5.917%2C0%2C11.833H3.525l3.3-5.722%2C3.3%2C5.722h3.525L11.888%2C8.768l0%2C0L10.247%2C5.917l-.118-.205L8.594%2C3.053Z'%20transform='translate(11.833)%20rotate(90)'%20fill='%23fff'/%3E%3C/svg%3E");pointer-events:none;}
}










@media (min-width: 1292px){
body{margin:0; font-family:"Noto Sans JP",system-ui,-apple-system,"Segoe UI","Hiragino Kaku Gothic ProN","Noto Sans JP","Yu Gothic",sans-serif; width:100%; overflow-x:hidden;color:#0f172a; font-size:16px; line-height:1.2;}
#spMenu,.spnavi {display: none;}
.bg{position:fixed; z-index:1; top:0; left:0; width:100%; height:100%;}
.bg img{width:100%; height:100%; object-fit:cover; object-position:top;}

/* ヘッダー全体 */
.cl-header{position:sticky; /* 必要なければ fixed / static に変えてOK */ top:0; z-index:100; background:linear-gradient(to right,#f2f7ff,#ffffff); backdrop-filter:blur(10px); border-bottom:1px solid rgba(148,163,184,0.25);}
.cl-header-inner{max-width:1292px; margin:0 auto; padding:16px 0; display:flex; align-items:center; justify-content:space-between;}
/* ロゴ部分 */
.cl-logo{display:flex; align-items:center; gap:12px;}
.cl-logo-mark{width:50px; height:50px;}
.cl-logo-mark svg{width:100%; height:100%; object-fit:contain;}
.cl-logo-text-main{font-weight:700; font-size:21px; color:#0f172a; font-weight:bold;}
.cl-logo-text-sub{font-size:12px; color:#64748b;}
/* ナビゲーション */
.cl-nav{display:flex; align-items:center; gap:61px; font-size:13px;}
.cl-nav-item{text-align:center; line-height:1.3; cursor:pointer;}
.cl-nav-label-ja{color:#0f172a; font-weight:bold; font-size:14px; white-space:nowrap;}
.cl-nav-label-en{color:#94a3b8; font-size:10px; text-transform:uppercase;}
.cl-nav-item:hover .cl-nav-label-ja{color:#2563eb;}

main{position:relative; z-index:2; padding-bottom: 160px; overflow:hidden;}

main ul{width:55%; display:flex; justify-content:flex-start; flex-wrap:wrap; gap:8px 36px; padding:0; margin:0 0 36px;}
main li{position:relative; padding-left:40px; list-style-type:none; width:100%; height:36px; display:block; display:flex; justify-content:flex-start; align-items:center; font-weight:bold;}
main li::before{content:""; position:absolute; left:0; top:0.15em; /* 行の中心にしたいなら 50% + translateY を使う（下に例） */ width:36px; /* ←アイコン表示サイズ */ height:36px; /* ←アイコン表示サイズ */ background-repeat:no-repeat; background-position:center; background-size:contain; background-image:url("data:image/svg+xml,%3Csvg%20width='38'%20height='38'%20viewBox='0%200%2038%2038'%20fill='none'%20xmlns='http://www.w3.org/2000/svg'%3E%3Cg%20filter='url(%23filter0_d_1_121)'%3E%3Ccircle%20cx='17'%20cy='15'%20r='15'%20fill='white'/%3E%3C/g%3E%3Cg%20filter='url(%23filter1_i_1_121)'%3E%3Ccircle%20cx='17'%20cy='15'%20r='13'%20fill='%23FAFAFA'/%3E%3C/g%3E%3Cpath%20d='M19.7332%2011.7311L15.2605%2016.3027L13.9641%2014.9775C13.4804%2014.4832%2012.695%2014.4832%2012.2114%2014.9775C11.7277%2015.4719%2011.7277%2016.2732%2012.2114%2016.769L14.3842%2018.9899C14.6267%2019.2378%2014.9429%2019.3603%2015.2605%2019.3603C15.5781%2019.3603%2015.8943%2019.2363%2016.1369%2018.9899L21.4859%2013.5225C21.9695%2013.0282%2021.9695%2012.2269%2021.4859%2011.7311C21.0022%2011.2367%2020.2169%2011.2367%2019.7332%2011.7311Z'%20fill='%233B82F6'/%3E%3Cdefs%3E%3Cfilter%20id='filter0_d_1_121'%20x='0'%20y='0'%20width='38'%20height='38'%20filterUnits='userSpaceOnUse'%20color-interpolation-filters='sRGB'%3E%3CfeFlood%20flood-opacity='0'%20result='BackgroundImageFix'/%3E%3CfeColorMatrix%20in='SourceAlpha'%20type='matrix'%20values='0%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200%20127%200'%20result='hardAlpha'/%3E%3CfeOffset%20dx='2'%20dy='4'/%3E%3CfeGaussianBlur%20stdDeviation='2'/%3E%3CfeComposite%20in2='hardAlpha'%20operator='out'/%3E%3CfeColorMatrix%20type='matrix'%20values='0%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200.07%200'/%3E%3CfeBlend%20mode='normal'%20in2='BackgroundImageFix'%20result='effect1_dropShadow_1_121'/%3E%3CfeBlend%20mode='normal'%20in='SourceGraphic'%20in2='effect1_dropShadow_1_121'%20result='shape'/%3E%3C/filter%3E%3Cfilter%20id='filter1_i_1_121'%20x='4'%20y='2'%20width='26'%20height='30'%20filterUnits='userSpaceOnUse'%20color-interpolation-filters='sRGB'%3E%3CfeFlood%20flood-opacity='0'%20result='BackgroundImageFix'/%3E%3CfeBlend%20mode='normal'%20in='SourceGraphic'%20in2='BackgroundImageFix'%20result='shape'/%3E%3CfeColorMatrix%20in='SourceAlpha'%20type='matrix'%20values='0%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200%20127%200'%20result='hardAlpha'/%3E%3CfeOffset%20dy='4'/%3E%3CfeGaussianBlur%20stdDeviation='2'/%3E%3CfeComposite%20in2='hardAlpha'%20operator='arithmetic'%20k2='-1'%20k3='1'/%3E%3CfeColorMatrix%20type='matrix'%20values='0%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200%200.12%200'/%3E%3CfeBlend%20mode='normal'%20in2='shape'%20result='effect1_innerShadow_1_121'/%3E%3C/filter%3E%3C/defs%3E%3C/svg%3E");}

main .btnbox a{display:block;width:300px;  height:auto; text-align:center; border-radius:30px; padding:16px 32px; margin:0 32px 0 0; line-height:1;}
main .btnbox a:first-child{background:linear-gradient(180deg,#51C3D5 0%,#2B5AF4 100%); box-shadow:3px 3px 4px 0 rgba(0,0,0,0.15); color:#fff; font-weight:bold;}
main .btnbox a:last-child{background:linear-gradient(180deg,#FFF 0%,#E8E4E4 100%);box-shadow:3px 3px 4px 0 rgba(0,0,0,0.15); font-weight:bold; color:#0f172a;}

.kv{position:relative; width:1292px; height:auto; margin:0 auto;}
.kv .txt{padding:0 0 0 24px;}
.kv .txt h1{font-size:61px; font-weight:900; margin-bottom:36px; line-height:1.2;}
.kv .txt p{line-height:1.6;display:block; margin-bottom:36px;}

.kv .txt .btnbox{width:100%; display:flex; justify-content:flex-start;}
.kv img{position:absolute; z-index:2; left:40%; top:56px; width:878px; height:609px;}

section{width:1292px; margin:0 auto 0; padding-top: 160px;}
section.subpagesec { width: 1000px; margin: 100px auto 0; padding: 64px; box-sizing: border-box; background: rgba(255,255,255,.4);}
section span {color: #3B82F6;}
section .ttlbox{text-align:center; margin-bottom:56px;}
section .ttlbox h2{font-size:51px; font-weight:700; text-align:center; margin-bottom: 16px;}
section .ttlbox p{ display: block; margin: 0 auto 0;}

section .tricell{width:100%; display:flex; justify-content:flex-start; flex-wrap:wrap; gap:16px;}
section .tricell .cell{display:flex; justify-content:center; align-items:center; flex-direction:column; width:420px; padding: 40px 0; box-sizing:border-box;  background:rgba(255,255,255,.7); border-radius:8px; backdrop-filter:blur(12px);}
section .tricell .cell h3{--icon-color:#ef6f6f; /* ← SVG の色 */ position:relative; padding-left:38px; /* アイコン分の余白（サイズに応じて調整） */ font-size:18px; margin-bottom: 24px;}
section .tricell .cell h3::before{content:""; position:absolute; left:0; top:50%; transform:translateY(-50%); width:30px; /* アイコンサイズ */ height:auto; aspect-ratio:20/18; /* viewBox(33x30)比率に近い値 */ background-color:var(--icon-color); -webkit-mask:url("data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%2033%2030'%3E%3Cpath%20d='m27.63%2C30H5.37c-1.94%2C0-3.68-1-4.64-2.68-.97-1.68-.97-3.68%2C0-5.36L11.86%2C2.68c.97-1.68%2C2.71-2.68%2C4.64-2.68s3.68%2C1%2C4.64%2C2.68l11.13%2C19.27c.97%2C1.68.97%2C3.68%2C0%2C5.36-.97%2C1.68-2.71%2C2.68-4.64%2C2.68ZM16.5%2C1.54c-1.38%2C0-2.62.71-3.31%2C1.91L2.06%2C22.73c-.69%2C1.2-.69%2C2.63%2C0%2C3.82.69%2C1.2%2C1.93%2C1.91%2C3.31%2C1.91h22.26c1.38%2C0%2C2.62-.71%2C3.31-1.91.69-1.2.69-2.63%2C0-3.82L19.81%2C3.45c-.69-1.2-1.93-1.91-3.31-1.91Zm13.45%2C21.27L19.22%2C4.23c-.57-.98-1.59-1.57-2.72-1.57s-2.15.59-2.72%2C1.57L3.05%2C22.81c-.57.98-.57%2C2.16%2C0%2C3.14.57.98%2C1.59%2C1.57%2C2.72%2C1.57h21.46c1.14%2C0%2C2.15-.59%2C2.72-1.57.57-.98.57-2.16%2C0-3.14Zm-15.4-12.79c0-1.07.87-1.94%2C1.94-1.94s1.94.87%2C1.94%2C1.94v6.31c0%2C1.07-.87%2C1.94-1.94%2C1.94s-1.94-.87-1.94-1.94v-6.31Zm1.94%2C13.98c-1.1%2C0-1.99-.89-1.99-1.99s.89-1.99%2C1.99-1.99%2C1.99.89%2C1.99%2C1.99-.89%2C1.99-1.99%2C1.99Z'/%3E%3C/svg%3E") no-repeat center / contain;mask:url("data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%2033%2030'%3E%3Cpath%20d='m27.63%2C30H5.37c-1.94%2C0-3.68-1-4.64-2.68-.97-1.68-.97-3.68%2C0-5.36L11.86%2C2.68c.97-1.68%2C2.71-2.68%2C4.64-2.68s3.68%2C1%2C4.64%2C2.68l11.13%2C19.27c.97%2C1.68.97%2C3.68%2C0%2C5.36-.97%2C1.68-2.71%2C2.68-4.64%2C2.68ZM16.5%2C1.54c-1.38%2C0-2.62.71-3.31%2C1.91L2.06%2C22.73c-.69%2C1.2-.69%2C2.63%2C0%2C3.82.69%2C1.2%2C1.93%2C1.91%2C3.31%2C1.91h22.26c1.38%2C0%2C2.62-.71%2C3.31-1.91.69-1.2.69-2.63%2C0-3.82L19.81%2C3.45c-.69-1.2-1.93-1.91-3.31-1.91Zm13.45%2C21.27L19.22%2C4.23c-.57-.98-1.59-1.57-2.72-1.57s-2.15.59-2.72%2C1.57L3.05%2C22.81c-.57.98-.57%2C2.16%2C0%2C3.14.57.98%2C1.59%2C1.57%2C2.72%2C1.57h21.46c1.14%2C0%2C2.15-.59%2C2.72-1.57.57-.98.57-2.16%2C0-3.14Zm-15.4-12.79c0-1.07.87-1.94%2C1.94-1.94s1.94.87%2C1.94%2C1.94v6.31c0%2C1.07-.87%2C1.94-1.94%2C1.94s-1.94-.87-1.94-1.94v-6.31Zm1.94%2C13.98c-1.1%2C0-1.99-.89-1.99-1.99s.89-1.99%2C1.99-1.99%2C1.99.89%2C1.99%2C1.99-.89%2C1.99-1.99%2C1.99Z'/%3E%3C/svg%3E") no-repeat center / contain;}
section .tricell .cell .img {width: 66px; height: 66px; text-align: center;aspect-ratio: 1 / 1;}
section .tricell .cell .img img {width: 100%; height: 100%; object-fit:cover;}

section .quadcell{ width:100%; display:flex; justify-content:flex-start; flex-wrap:wrap; gap:16px;}
section .quadcell .cell{display:flex; justify-content:center; align-items:center; flex-direction:column; width:311px; box-sizing:border-box; padding: 40px 0; box-sizing:border-box;  background:rgba(255,255,255,.7); border-radius:8px; backdrop-filter:blur(12px);}
section .quadcell .cell h3{font-size:18px; margin-bottom: 24px; }
section .quadcell .cell .img {width: 45%; height: auto; text-align: center; margin-bottom: 24px;}
section .quadcell .cell .img img {width: 100%; height: 100%; object-fit:cover;}
section .quadcell .cell p{font-size:13px; text-align: center; }

section .voicecell{width:100%; display:flex; justify-content:flex-start; flex-wrap:wrap; gap:16px;}
section .voicecell .cell{position: relative; display:block; width:420px; box-sizing:border-box; padding: 40px; box-sizing:border-box;  background:rgba(255,255,255,.7); border-radius:8px; backdrop-filter:blur(12px);}
section .voicecell .cell .icon { width: 100%; display: flex; justify-content: flex-start; align-items: center; margin-bottom: 32px;}
section .voicecell .cell .icon svg { width: 15%; height: auto; margin-right: 16px;}
section .voicecell .cell .voice{position:relative; text-align: left; width: 100%; font-weight: bold; font-size: 18px; line-height: 1.7; padding: 16px; box-sizing: border-box;}
section .voicecell .cell .voice::before{content:"“";position:absolute;left:0;top:0;font-size:40px;line-height:1;color:#3B82F6;transform:translate(-20%,-35%);pointer-events:none;}
section .voicecell .cell .voice::after{content:"”";position:absolute;right:0;bottom:0;font-size:40px;line-height:1;color:#3B82F6;transform:translate(20%,35%);pointer-events:none;}

section .servicecell{width:100%; display:flex; justify-content:flex-start; flex-wrap:wrap; gap:16px;}
section .servicecell .cell{ display:block; width:420px; padding: 32px; box-sizing:border-box;  background:rgba(255,255,255,.7); border-radius:8px; backdrop-filter:blur(12px); line-height: 1.7;}
section .servicecell .cell .top{ display:flex; justify-content: space-between; align-items: center; flex-direction: row-reverse; }
section .servicecell .cell .top .img { width: 15%; }
section .servicecell .cell .top .img img {width: 100%; height: 100%; object-fit:cover;}
section .servicecell .cell .top .ttlp { width: 85%; font-size: 21px; margin: 0; font-weight: bold; color: #3B82F6; padding-left: 24px;}
section .servicecell .cell .top .ttlp span {font-size: 13px; display: block;}

section .stepcell{width:100%;display:flex;justify-content:flex-start;gap:8px;align-items:stretch;background:rgba(255,255,255,.7);border-radius:8px;backdrop-filter:blur(12px);text-align:center;padding:56px 32px; box-sizing:border-box;}
section .stepcell .cell{position:relative;flex:1;box-sizing:border-box;}
section .stepcell .cell:nth-child(-n+5){padding-right:56px;}
section .stepcell .cell:nth-child(-n+5)::after{content:"";position:absolute;right:10px;top:50%;transform:translateY(-50%);width:25px;height:25px;background-repeat:no-repeat;background-position:center;background-size:contain;background-image:url("data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%2010%2010'%3E%3Cpath%20d='M2%201L8%205L2%209Z'%20fill='%23000'/%3E%3C/svg%3E"); opacity: .2;}
section .stepcell .cell span { display: block; border-radius: 8px; background:linear-gradient(180deg,#51C3D5 0%,#2B5AF4 100%); color: #fff; text-align: center; padding: 8px; font-size: 12px; margin-bottom: 16px;}
section .stepcell .cell .img { width: 60%; height: auto; margin: 0 auto 16px; }
section .stepcell .cell .img img {width: 100%; height: 100%; object-fit:cover;}
section .stepcell .cell p.secttl {color: var(--c1); font-size: 16px; font-weight: bold;}
section .stepcell .cell p.btm {text-align: left; line-height: 1.7; font-size: 13px;}


section .pricecell{width:100%; display:flex; justify-content:flex-start; gap:16px; margin-bottom: 48px;}
section .pricecell .cell{position: relative; display:flex; justify-content:flex-start; align-items:center;flex-direction: column; flex-wrap:wrap; width:420px; padding: 32px; box-sizing:border-box;  background:rgba(255,255,255,.7); border-radius:8px; backdrop-filter:blur(12px);}
section .pricecell .cell .plangrade { position: relative; display: block; width: 100%; border-radius: 8px; background:#51C3D5; color: #fff; text-align: left; padding: 16px 24px 16px 72px; font-size: 16px; margin-bottom: 16px; box-sizing: border-box;}
section .pricecell .cell .plangrade img {position: absolute; z-index: 2; left: 15px; top: 20px; width: 46px; height: auto;}
section .pricecell .cell .plangrade span {display: block; font-size: 28px; color: #fff; margin-bottom: 1px; }
section .pricecell .cell:nth-of-type(1) .plangrade { background:#3B82F6;}
section .pricecell .cell:nth-of-type(2) .plangrade { background:#1C72FF;}
section .pricecell .cell:nth-of-type(3) .plangrade { background:#0357E0;}
section .pricecell .cell .price {font-size: 64px; display: block; width: 100%; font-weight: 700; text-align: center; margin: 0 auto 16px; padding-bottom: 24px; border-bottom: 1px dotted #94A3B8;}
section .pricecell .cell .price .lrg { display: inline; font-size: 21px; font-weight: normal; padding-left: 2px;}
section .pricecell .cell .price .sml {font-size: 16px; display: block; font-weight: 500; margin-top: 4px;}
section .pricecell .cell .price span {color: #0f172a!important;}
section .pricecell .cell ul { display: block; width: 100%; margin-bottom: 0; border-bottom: 1px dotted #94A3B8; padding-bottom: 16px; margin-bottom: 0; }
section .pricecell .cell ul li {margin-bottom: 8px; width: 100%;}
section .pricecell .cell ul li:last-child {margin-bottom: 0;}
section .pricecell .cell .btnbox{width:100%; display:flex; justify-content:center;}
section .pricecell .cell .btnbox a{display:block;width:fit-content; height:51px; text-align:center; border-radius:30px; padding:16px 32px; margin:0 auto; line-height:1;}
section .pricecell .cell .btnbox a{background:linear-gradient(180deg,#FFF 0%,#E8E4E4 100%);box-shadow:3px 3px 4px 0 rgba(0,0,0,0.15); font-weight:500; color:#0f172a;}

section .opcionbox{width:100%;display:block; text-align:center; box-sizing:border-box; margin-bottom: 80px;}
section .opcionbox h3 {width: 100%; height: auto; padding: 16px 0; margin: 0 auto 16px; font-size: 28px; }
section .opcionbox .box { flex:1; display:flex; justify-content:flex-start; flex-wrap:wrap; gap:16px;}
section .opcionbox .box .cell {position: relative; width: 19%; text-align: center; list-style-type:none; font-weight:bold; box-sizing:border-box; padding: 40px 24px; box-sizing:border-box;  background:rgba(255,255,255,.7); border-radius:8px;}
section .opcionbox .box .cell .icon img { display: block; width: 32%; height: auto; margin: 0 auto 16px; }
section .opcionbox .box .cell .opname {color: var(--c1); display: block; margin: 16px auto 2px;}
section .opcionbox .box .cell .price {display: flex; justify-content: center; align-items: flex-end;font-size: 28px; margin: 16px auto 1px;}
section .opcionbox .box .cell .price span { color: var(--fc1); display: inline; margin: 0 ; font-size: 12px; font-weight: normal;}
section .opcionbox .box .cell .price2 {display: flex; justify-content: center; align-items: flex-end; font-size: 16px; margin: 0 auto;}
section .opcionbox .box .cell .price2 span { color: var(--fc1); display: inline; margin: 0 ; font-size: 10px;}

.cta .btnbox {width: fit-content; margin: 0 auto; display:flex; justify-content: center; align-items:center;}
.cta .btnbox a {width: 300px;}


section .company{width:var(--max);margin: 0 auto;display:block;background:rgba(255,255,255,.7);border-radius:8px;backdrop-filter:blur(12px);text-align:left;padding:56px 32px; box-sizing:border-box;}
section .company .cell {width:100%; display:flex; justify-content:flex-start; margin-bottom: 16px; padding-bottom: 16px; border-bottom: dotted 1px #dadada;}
section .company .cell:nth-last-of-type(1) {margin-bottom: 0;}
section .company .cell:nth-of-type(1) { padding-top: 16px; border-top: dotted 1px #dadada;}
section .company .cell .cpttl {width:20%; text-align-last: left;}
section .company .cell .cpdd {width:80%; text-align-last: left;}
section .company .cell p {margin: 0;}

section #cta{width:60%;margin: 0 auto;display:block;background:rgba(255,255,255,.7);border-radius:8px;backdrop-filter:blur(12px);text-align:left;padding:56px 32px; box-sizing:border-box;}
section #cta form .cell {width:100%; display:flex; justify-content:flex-start; margin-bottom: 16px;}

.pagettl {width: 100%; margin: 60px auto 0; text-align: center;}
.box h2 {color: #3B82F6;}
.duocell {display: flex; justify-content: flex-start; align-items: center; margin-bottom: 16px;}
.duocell p:first-child {width: 20%;}
.duocell p:last-child {width: 80%;}
.subpagesec .box {margin-bottom: 60px;}
.subpagesec p { line-height: 1.6;}


footer{position:relative; z-index:2; overflow:hidden; background: #fff; text-align: center;}
footer .top { width: 100%; display: flex; justify-content: center; align-items: center; padding: 8px; box-sizing: border-box;}
footer .top a {display: block; font-size: 13px; margin: 0 8px 8px; color: #0f172a; }
footer .top a:hover {color: #3B82F6;}
footer .btm {padding: 8px; box-sizing: border-box; text-align: center; color: #fff; background: #3B82F6;}

input[type="text"],textarea{margin:0;padding:0;border:0;border-radius:0;background:none;font:inherit;color:inherit;outline:none;appearance:none;-webkit-appearance:none;}
#form { padding-top: 100px; margin-top: 0; overflow: hidden; width: 60%;}
#form form {}
#form form input,textarea { display: block; width: 100%; padding: 16px; box-sizing: border-box; background: #fff; border: none; border-radius: 8px; margin-bottom: 16px; font-size: 16px;}
#form form .ppagree{width: fit-content; margin: 16px auto;padding:16px;border:1px solid rgba(0,0,0,.12);border-radius:8px;background:rgba(255,255,255,1);}
#form form .ppagree__label{display:flex;align-items:center;gap:10px;cursor:pointer;user-select:none;}
#form form .ppagree__check{width:18px;height:18px;margin:0;accent-color:#3B82F6;}
#form form .ppagree__txt{line-height:1.5; color: #3B82F6;}
#form form .ppagree__note{margin:0 0 16px;font-size:12px;line-height:1.6;color:rgba(0,0,0,.65); text-align: center;}
#form form .ppagree__link{color:#3B82F6;text-decoration:underline;}
#form form .ppagree__link:hover{text-decoration:none;}
#form form button { position: relative; display: block; width: 40%; margin: 0 auto; padding: 16px 0 16px; background:linear-gradient(180deg,#51C3D5 0%,#2B5AF4 100%); box-shadow:3px 3px 4px 0 rgba(0,0,0,0.15); color:#fff; font-weight:bold; text-align: center; border-radius: 4px; transition: all .5s; border: none; font-size: 18px;}
#form form button span {  color: #fff; display: block; text-align: center;}
#form form button::after{content:"";position:absolute;top:40%;right:16px;width:10px;height:12px;background-repeat:no-repeat;background-position:center;background-size:contain;background-image:url("data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20width='11.832'%20height='13.658'%20viewBox='0%200%2011.832%2013.658'%3E%3Cpath%20d='M6.831%2C0l0%2C0%2C0%2C0L5.064%2C3.053l0%2C0L3.416%2C5.917%2C0%2C11.833H3.525l3.3-5.722%2C3.3%2C5.722h3.525L11.888%2C8.768l0%2C0L10.247%2C5.917l-.118-.205L8.594%2C3.053Z'%20transform='translate(11.833)%20rotate(90)'%20fill='%23fff'/%3E%3C/svg%3E");pointer-events:none;}

}

</style>
