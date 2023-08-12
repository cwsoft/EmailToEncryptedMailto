# 📧 EmailToEncryptedMailto

[ProcessWire](https://processwire.com) module to convert text emails and regular mailto links into encrypted mailto links like `<a href="javascript:cdc('dw:ckfv:e.frzfdw:esyfd','Ihre AnfrageV')">
info<span hidden>(</span>@<span hidden>)</span>domain<span hidden>(</span>.<span hidden>)</span>com</a>` to hide them from spam bots. Once clicked, the link will be decrypted by the Javascript method defined in _cdc.min.js_, which gets automatically linked to your template head section.

## Installation

Download Zip file from [Github](https://github.com/cwsoft/EmailToEncryptedMailto/releases) to your site/modules folder, unzip it and rename the module folder into **EmailToEncryptedMailto**. Alternatively clone the repository into your Processwire site/modules folder (recommended) using the following commands:

```
cd /your_processwire_folder/site/modules
git clone https://github.com/cwsoft/EmailToEncryptedMailto.git ./EmailToEncryptedMailto
```

Once the module files are copied in place, login to your ProcessWire backend and reload the modules. Afterwards the **EmailToEncryptedMailto** module should show up in your backend ready to be installed by ProcessWire as usual. Once installed, view a page with text email(s) in your frontend to see the module in action. You may want to install the German language file shipped with the module. For details see section Language files of the README.

## Customization

By default the characters `[@.]` are wrapped in paranthesis added via span tags in the visible mailto part to trick spam bots. The paranthesis `()` are hidden by default from human beeings via the HTML attribute `hidden`. The module comes with zero configuration and works out of the box.

### Language files

Enrypted mailto links automatically get a mail subject "Your Request" (Default), respective "Ihre Anfrage" (German). If you want to use the German language file, you need to install it from the module installation section in the backend. Learn how to install language files or add translation files yourself by following the steps described in the [Helloworld module](https://processwire.com/modules/helloworld/) by Ryan Cramer.

Apart from the language file, no customizations are available. Idea was to keep this module as clean and lean as possible. If you need additional features or want to customize stuff to your needs, you may want to test out other E-Mail obfuscation modules available in the official [ProcessWire modules catalog](https://processwire.com/modules/category/email/).

Have fun
cwsoft
