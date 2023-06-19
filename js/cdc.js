"use strict";
/**
 * Simple Caesar decryption method to turn encrypted mailto links into
 * clickable human readable mailto links.
 */

/**
 * Cecrypts encrypted mailto links to keep mailbots out.
 * @param a Encrypted href part of the mailto string.
 * @param b Subject of the Email with secret char at the end.
 */
function cdc(a, b) {
  var c = b[b.length - 1].charCodeAt(0) - 64;
  var d = "abcdefghijklmnopqrstuvwxyz@.-_:";
  var e = d.length - 1;
  var f = 0;
  var x = "";
  for (var g = 0; g < a.length; g++) {
    f = (d.indexOf(a[g]) - c) % e;
    var h = f < 0 ? f + e + 1 : f;
    x += d[h];
  }
  // Replace encrypted mailto href part with decrypted string.
  location.href = x + "?subject=" + escape(b.substr(0, b.length - 1));
}
