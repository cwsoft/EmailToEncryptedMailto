"use strict";
/**
 * Caesar decryption method to turn an encrypted mailto link with a subject string
 * into a clickable human readable mailto link to keep out most of the spambots.
 * @param {string} mailto Encrypted mailto part of the email.
 * @param {string} subject Subject of the mailto message with shift char appended.
 */
function cdc(mailto: string, subject: string): void {
  const allowedCharacters = "abcdefghijklmnopqrstuvwxyz@.-_:";
  const possibleOffsets = allowedCharacters.length - 1;
  const shiftedCharacters = subject[subject.length - 1].charCodeAt(0) - 64;
  let shiftedIdx = 0;
  let decryptedMailto = "";
  for (let idx = 0; idx < mailto.length; idx++) {
    shiftedIdx = (allowedCharacters.indexOf(mailto[idx]) - shiftedCharacters) % possibleOffsets;
    let plainIndex = shiftedIdx < 0 ? shiftedIdx + possibleOffsets + 1 : shiftedIdx;
    decryptedMailto += allowedCharacters[plainIndex];
  }
  // Replace encrypted mailto href part by decrypted mailto string.
  location.href = `${decryptedMailto}?subject=${encodeURIComponent(subject.slice(0, subject.length - 1))}`;
}
