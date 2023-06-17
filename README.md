# 👀 EmailToEncryptedMailto

[ProcessWire](https://processwire.com) site module to turn text emails like `info@domain.com` into encrypted mailto links like **<a href="javascript:cdc('dw:ckfv:e.frzfdw:esyfd','Ihre AnfrageV')">info<span class="cdc">(</span>@<span class="cdc">)</span>domain<span class="cdc">(</span>.<span class="cdc">)</span>com</a>** to hide them from spam bots. Once clicked, the link will be decrypted by the Javascript method defined in *cdc.min.js* autoloaded by the module to your template.

## Installation

Download Zip file from [Releases section](https://github.com/cwsoft/pwEmailToEncryptedMailto/releases) to your site/modules, unzip it and rename the module folder into **EmailToEncryptMailto**. Alternatively you can clone the repository into your Processwire site/modules folder (recommended) via the following commands:
```
cd /your_processwire_folder/site/modules
git clone https://github.com/cwsoft/pwEmailToEncryptedMailto.git ./EmailToEncryptedMailto
```
Once the module files are copied in place, login to your ProcessWire backend and reload the modules. Afterwards the **EmailToEncryptMailto** module should show up in your backend ready to be installed by ProcessWire as usual. Once installed, view a page with text email(s) in your frontend to see the module in action.

## Customization

### CSS styles

By default paranthesis are wrapped around `@` and `.` characters enclosed in span tags in the visible mailto link part to trick spam bots. Those paranthesis "(@)" and "(.)" are hidden by default from human beeings via the CSS class "cdc" added to the span tags by the module. If you want to display the paranthesis or change other parts of the <span class="cdc">, just modify the modules CSS file.

```CSS
.cdc {
  display: none;
}
```

### Language files

By default the mailto emails include a mail subject translated into **en** (Your Request) and **de** (Ihre Anfrage) by default. If you want to add another language file, please follow the translation steps described in the [Helloworld module](https://processwire.com/modules/helloworld/) by Ryan Cramer.

Apart from the CSS and languages, no furhter customizations are yet available. Idea was to keep this module as clean and lean as possible. If you need additional features or want to customize stuff to your needs, you may want to test out other E-Mail obfuscation modules available in the official [ProcessWire modules catalog](https://processwire.com/modules/category/email/).

Have fun
cwsoft
