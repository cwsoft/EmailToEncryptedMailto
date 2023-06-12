# 👀 EmailToEncryptedMailto
[ProcessWire](https://processwire.com) site module to turn text emails like `info@domain.com` into encrypted mailto links like `<a href="javascript:cdc('dw:ckfv:e.frzfdw:esyfd','AnfrageV')">info<span class="hidden">(</span>@<span class="hidden">)</span>domain<span class="hidden">(</span>.<span class="hidden">)</span>com</a>` to hide them from spam bots. Once clicked, the link will be decrypted by the Javascript method `cdc.min.js` autoloaded by the module to your template.

## Installation
Download Zip and extract files to site/modules/EmailToEncryptMailto. Then login to your ProcessWire backend and reload modules. After reload the `EmailToEncryptMailto` module should show up in your backend ready to be `installed` by ProcessWire. Install it and then view a page with a simple text email in your frontend to see it in action.

### Customization
By default paranthesis are wrapped around `@` and `.` characters of the visible mailto link parts to trick spam bots. You can hide the paranthesis "(@)" and "(.)" from human beeings by adding a class "hidden" to your template CSS file with the following content.

```CSS
.hidden {
  display: none;
}
```

Apart from that, no other customization is available. If you need additional features or want to customize stuff to your needs, you want to test out the other E-Mail obfuscation modules available in the official [ProcessWire modules catalog](https://processwire.com/modules/category/email/).

Have fun 
cwsoft