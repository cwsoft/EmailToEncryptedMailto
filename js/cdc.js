"use strict";function cdc(o,e){const n="abcdefghijklmnopqrstuvwxyz@.-_:",c=n.length-1,s=e[e.length-1].charCodeAt(0)-64;let t=0,d="";for(let l=0;l<o.length;l++){t=(n.indexOf(o[l])-s)%c;let h=t<0?t+c+1:t;d+=n[h]}location.href=`${d}?subject=${encodeURIComponent(e.slice(0,e.length-1))}`}
