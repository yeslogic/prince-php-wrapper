Prince PHP wrapper
==================

The `prince.php` file defines a class called `Prince` that contains methods
that can be called to convert HTML and XML documents into PDF.

Note that the `Prince` class requires PHP 5.3.0 or later.

Constructor
-----------

When instantiating the `Prince` class, pass in the full path of the Prince
executable to the constructor as a string argument. For example, on
Linux or MacOS X:

```php
use Prince\Prince;

$prince = new Prince('/usr/bin/prince');
```

On Windows, be sure to specify the path to the `prince.exe` file located
within the `Engine\bin` subfolder of the Prince installation.

Conversion methods
------------------

-   [`convert_file`](#convert_file)
-   [`convert_file_to_file`](#convert_file_to_file)
-   [`convert_multiple_files`](#convert_multiple_files)
-   [`convert_string_to_file`](#convert_string_to_file)
-   [`convert_file_to_passthru`](#convert_file_to_passthru)
-   [`convert_multiple_files_to_passthru`](#convert_multiple_files_to_passthru)
-   [`convert_string_to_passthru`](#convert_string_to_passthru)

Configuration methods
---------------------

-   [`addStyleSheet`](#addstylesheet)
-   [`clearStyleSheets`](#clearstylesheets)
-   [`addScript`](#addscript)
-   [`clearScripts`](#clearscripts)
-   [`addFileAttachment`](#addfileattachment)
-   [`clearFileAttachments`](#clearfileattachments)
-   [`setLicenseFile`](#setlicensefile)
-   [`setLicenseKey`](#setlicensekey)
-   [`setInputType`](#setinputtype)
-   [`setHTML`](#sethtml)
-   [`setJavaScript`](#setjavascript)
-   [`setLog`](#setlog)
-   [`setBaseURL`](#setbaseurl)
-   [`setXInclude`](#setxinclude)
-   [`setHttpUser`](#sethttpuser)
-   [`setHttpPassword`](#sethttppassword)
-   [`setHttpProxy`](#sethttpproxy)
-   [`setHttpTimeout`](#sethttptimeout)
-   [`setInsecure`](#setinsecure)
-   [`setFileRoot`](#setfileroot)
-   [`setEmbedFonts`](#setembedfonts)
-   [`setSubsetFonts`](#setsubsetfonts)
-   [`setNoArtificialFonts`](#setnoartificialfonts)
-   [`setAuthMethod`](#setauthmethod)
-   [`setAuthUser`](#setauthuser)
-   [`setAuthPassword`](#setauthpassword)
-   [`setAuthServer`](#setauthserver)
-   [`setAuthScheme`](#setauthscheme)
-   [`setNoAuthPreemptive`](#setnoauthpreemptive)
-   [`setPageSize`](#setpagesize)
-   [`setPageMargin`](#setpagemargin)
-   [`setCompress`](#setcompress)
-   [`setPDFTitle`](#setpdftitle)
-   [`setPDFSubject`](#setpdfsubject)
-   [`setPDFAuthor`](#setpdfauthor)
-   [`setPDFKeywords`](#setpdfkeywords)
-   [`setPDFCreator`](#setpdfcreator)
-   [`setEncrypt`](#setencrypt)
-   [`setEncryptInfo`](#setencryptinfo)
-   [`setOptions`](#setoptions)

------------------------------------------------------------------------

<a name="convert_file"></a>
```php
public function convert_file($xmlPath, &$msgs = array())
```

Convert an XML or HTML file to a PDF file. The name of the output PDF
file will be the same as the name of the input file but with an
extension of ".pdf". Returns true if a PDF file was generated
successfully.

`xmlPath`
:   The filename of the input XML or HTML document.

`msgs`
:   An optional array in which to return error and warning messages.
    Each message is returned as an array of three strings: the message
    code (`'err'`, `'wrn'` or `'inf'`), the message location (eg.
    a filename) and the message text.

`returns`
:   True if a PDF file was generated successfully, false otherwise.

------------------------------------------------------------------------

<a name="convert_file_to_file"></a>
```php
public function convert_file_to_file($xmlPath, $pdfPath, &$msgs = array())
```

Convert an XML or HTML file to a PDF file. Returns true if a PDF file
was generated successfully.

`xmlPath`
:   The filename of the input XML or HTML document.

`pdfPath`
:   The filename of the output PDF file.

`msgs`
:   An optional array in which to return error and warning messages.
    Each message is returned as an array of three strings: the message
    code (`'err'`, `'wrn'` or `'inf'`), the message location (eg.
    a filename) and the message text.

`returns`
:   True if a PDF file was generated successfully, false otherwise.

------------------------------------------------------------------------

<a name="convert_multiple_files"></a>
```php
public function convert_multiple_files($xmlPaths, $pdfPath, &$msgs = array())
```

Convert multiple XML or HTML files to a PDF file. Returns true if a PDF
file was generated successfully.

`xmlPaths`
:   An array of the input XML or HTML documents.

`pdfPath`
:   The filename of the output PDF file.

`msgs`
:   An optional array in which to return error and warning messages.
    Each message is returned as an array of three strings: the message
    code (`'err'`, `'wrn'` or `'inf'`), the message location (eg.
    a filename) and the message text.

`returns`
:   True if a PDF file was generated successfully, false otherwise.

------------------------------------------------------------------------

<a name="convert_string_to_file"></a>
```php
public function convert_string_to_file($xmlString, $pdfPath, &$msgs = array())
```

Convert an XML or HTML string to a PDF file. Returns true if a PDF file
was generated successfully.

`xmlString`
:   A string containing an XML or HTML document.

`pdfPath`
:   The filename of the output PDF file.

`msgs`
:   An optional array in which to return error and warning messages.
    Each message is returned as an array of three strings: the message
    code (`'err'`, `'wrn'` or `'inf'`), the message location (eg.
    a filename) and the message text.

`returns`
:   True if a PDF file was generated successfully, false otherwise.

------------------------------------------------------------------------

<a name="convert_file_to_passthru"></a>
```php
public function convert_file_to_passthru($xmlPath, &$msgs = array())
```

Convert an XML or HTML file to a PDF file, which will be passed through
to the output buffer of the current PHP page. Returns true if a PDF file
was generated successfully.

`xmlPath`
:   The filename of the input XML or HTML document.

`msgs`
:   An optional array in which to return error and warning messages.
    Each message is returned as an array of three strings: the message
    code (`'err'`, `'wrn'` or `'inf'`), the message location (eg.
    a filename) and the message text.

`returns`
:   True if a PDF file was generated successfully, false otherwise.

Note that to have the browser correctly display the PDF output, the
following two lines will be needed before the convert method is called:

```php
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="foo.pdf"');
```

You may also specify `attachment` for the Content-Disposition header
instead of `inline`, so that the browser will prompt the user to save
the PDF file instead of displaying it.

------------------------------------------------------------------------

<a name="convert_multiple_files_to_passthru"></a>
```php
public function convert_multiple_files_to_passthru($xmlPaths, &$msgs = array())
```

Convert multiple XML or HTML files to a PDF file, which will be passed
through to the output buffer of the current PHP page. Returns true if a
PDF file was generated successfully.

`xmlPaths`
:   An array of the input XML or HTML documents.

`msgs`
:   An optional array in which to return error and warning messages.
    Each message is returned as an array of three strings: the message
    code (`'err'`, `'wrn'` or `'inf'`), the message location (eg.
    a filename) and the message text.

`returns`
:   True if a PDF file was generated successfully, false otherwise.

Note that to have the browser correctly display the PDF output, the
following two lines will be needed before the convert method is called:

```php
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="foo.pdf"');
```

You may also specify `attachment` for the Content-Disposition header
instead of `inline`, so that the browser will prompt the user to save
the PDF file instead of displaying it.

------------------------------------------------------------------------

<a name="convert_string_to_passthru"></a>
```php
public function convert_string_to_passthru($xmlString, &$msgs = array())
```

Convert an XML or HTML string to a PDF file, which will be passed
through to the output buffer of the current PHP page. Returns true if a
PDF file was generated successfully.

`xmlString`
:   A string containing an XML or HTML document.

`msgs`
:   An optional array in which to return error and warning messages.
    Each message is returned as an array of three strings: the message
    code (`'err'`, `'wrn'` or `'inf'`), the message location (eg.
    a filename) and the message text.

`returns`
:   True if a PDF file was generated successfully, false otherwise.

Note that to have the browser correctly display the PDF output, the
following two lines will be needed before the convert method is called:

```php
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="foo.pdf"');
```

You may also specify `attachment` for the Content-Disposition header
instead of `inline`, so that the browser will prompt the user to save
the PDF file instead of displaying it.

------------------------------------------------------------------------

<a name="addstylesheet"></a>
```php
public function addStyleSheet($cssPath)
```

Add a CSS style sheet that will be applied to each input document. The
`addStyleSheet` function can be called more than once to add multiple
style sheets. This function can called before calling a convert
function.

`cssPath`
:   The filename of the CSS style sheet to apply.

------------------------------------------------------------------------

<a name="clearstylesheets"></a>
```php
public function clearStyleSheets()
```

Clear all of the CSS style sheets accumulated by calling
`addStyleSheet`.

------------------------------------------------------------------------

<a name="addscript"></a>
```php
public function addScript($jsPath)
```

Add a JavaScript script that will be run before conversion. The
`addScript` function can be called more than once to add multiple
scripts. This function can be called before calling a convert function.

`jsPath`
:   The filename of the script to run.

------------------------------------------------------------------------

<a name="clearscripts"></a>
```php
public function clearScripts()
```

Clear all of the scripts accumulated by calling `addScript`.

------------------------------------------------------------------------

<a name="addfileattachment"></a>
```php
public function addFileAttachment($filePath)
```

Add a file attachment that will be attached to the PDF file.
The`addFileAttachment` can be called more than once to add multiple file
attachments.

`filePath`
:   The filename of the file attachment.

------------------------------------------------------------------------

<a name="clearfileattachments"></a>
```php
public function clearFileAttachments()
```

Clear all of the file attachments accumulated by calling
`addFileAttachment`.

------------------------------------------------------------------------

<a name="setlicensefile"></a>
```php
public function setLicenseFile($file)
```

Specify the license file.

`file`
:   The filename of the license file.

------------------------------------------------------------------------

<a name="setlicensekey"></a>
```php
public function  setLicenseKey($key)
```

Specify the license key.

`key`
:   The license key.

------------------------------------------------------------------------

<a name="setinputtype"></a>
```php
public function setInputType($inputType)
```

Specify the input type of the document.

`inputType`
:   Can take a value of : "xml", "html" or "auto".

------------------------------------------------------------------------

<a name="sethtml"></a>
```php
public function setHTML($html)
```

Specify whether documents should be parsed as HTML or XML/XHTML.

`html`
:   True if all documents should be treated as HTML.

------------------------------------------------------------------------

<a name="setjavascript"></a>
```php
public function setJavaScript($js)
```

Specify whether JavaScript found in documents should be run.

`js`
:   True if document scripts should be run.

------------------------------------------------------------------------

<a name="setlog"></a>
```php
public function setLog($logFile)
```

Specify a file that Prince should use to log error/warning messages.

`logFile`
:   The filename that Prince should use to log error/warning messages,
    or '' to disable logging.

------------------------------------------------------------------------

<a name="setbaseurl"></a>
```php
public function setBaseURL($baseURL)
```

Specify the base URL of the input document.

`baseURL`
:   The base URL or path of the input document, or ''.

------------------------------------------------------------------------

<a name="setxinclude"></a>
```php
public function setXInclude($xinclude)
```

Specify whether XML Inclusions (XInclude) processing should be applied
to input documents. XInclude processing will be performed by default
unless explicitly disabled.

`xinclude`
:   False to disable XInclude processing.

------------------------------------------------------------------------

<a name="sethttpuser"></a>
```php
public function setHttpUser($user)
```

Specify a username to use when fetching remote resources over HTTP.

`user`
:   The username to use for basic HTTP authentication.

------------------------------------------------------------------------

<a name="sethttppassword"></a>
```php
public function setHttpPassword($password)
```

Specify a password to use when fetching remote resources over HTTP.

`password`
:   The password to use for basic HTTP authentication.

------------------------------------------------------------------------

<a name="sethttpproxy"></a>
```php
public function setHttpProxy($proxy)
```

Specify the URL for the HTTP proxy server, if needed.

`proxy`
:   The URL for the HTTP proxy server.

------------------------------------------------------------------------

<a name="sethttptimeout"></a>
```php
public function setHttpTimeout($timeout)
```

Specify the timeout for HTTP requests.

`timeout`
:   The HTTP timeout in seconds.

------------------------------------------------------------------------

<a name="setinsecure"></a>
```php
public function setInsecure($insecure)
```

Specify whether to disable SSL verification.

`insecure`
:   If set to true, SSL verification is disabled. (not recommended)

------------------------------------------------------------------------

<a name="setfileroot"></a>
```php
public function setFileRoot($fileRoot)
```

Specify the root directory for absolute filenames. This can be used when
converting a local file that uses absolute paths to refer to web
resources. For example, /images/logo.jpg can be rewritten to
/usr/share/images/logo.jpg by specifying "/usr/share" as the root.

`fileRoot`
:   The path to prepend to absolute filenames.

------------------------------------------------------------------------

<a name="setembedfonts"></a>
```php
public function setEmbedFonts($embedFonts)
```

Specify whether fonts should be embedded in the output PDF file. Fonts
will be embedded by default unless explicitly disabled.

`embedFonts`
:   False to disable PDF font embedding.

------------------------------------------------------------------------

<a name="setsubsetfonts"></a>
```php
public function setSubsetFonts($subsetFonts)
```

Specify whether embedded fonts should be subset in the output PDF file.
Fonts will be subset by default unless explicitly disabled.

`subsetFonts`
:   False to disable PDF font subsetting.

------------------------------------------------------------------------

<a name="setnoartificialfonts"></a>
```php
public function setNoArtificialFonts($noArtificialFonts)
```

Specify whether artificial bold/italic fonts should be generated if
necessary. Artificial fonts are enabled by default.

`noArtificialFonts`
:   True to disable artificial bold/italic fonts.

------------------------------------------------------------------------

<a name="setauthmethod"></a>
```php
public function setAuthMethod($authMethod)
```

Specify HTTP authentication methods. (basic, digest, ntlm, negotiate)

`authMethod`
:   The authentication method to use.

------------------------------------------------------------------------

<a name="setauthuser"></a>
```php
public function setAuthUser($authUser)
```

Specify username for HTTP authentication.

`authUser`
:   The authentication username to use.

------------------------------------------------------------------------

<a name="setauthpassword"></a>
```php
public function setAuthPassword($authPassword)
```

Specify password for HTTP authentication.

`authPassword`
:   The authentication password to use.

------------------------------------------------------------------------

<a name="setauthserver"></a>
```php
public function setAuthServer($authServer)
```

Only send USER:PASS to this server.

`authServer`
:   The server to send USER:PASS.

------------------------------------------------------------------------

<a name="setauthscheme"></a>
```php
public function setAuthScheme($authScheme)
```

Only send USER:PASS for this scheme. (HTTP, HTTPS)

`authScheme`
:   The scheme to use for authentication.

------------------------------------------------------------------------

<a name="setnoauthpreemptive"></a>
```php
public function setNoAuthPreemptive($noAuthPreemptive)
```

Do not authenticate with named servers until asked.

`noAuthPreemptive`
:   True to disable authentication preemptive.

------------------------------------------------------------------------

<a name="setpagesize"></a>
```php
public function setPageSize($pageSize)
```

Specify the page size (eg. A4).

`pageSize`
:   The page size to use.

------------------------------------------------------------------------

<a name="setpagemargin"></a>
```php
public function setPageMargin($pageMargin)
```

Specify the page margin (eg. 20mm).

`pageMargin`
:   The page magin to use.

------------------------------------------------------------------------

<a name="setcompress"></a>
```php
public function setCompress($compress)
```

Specify whether compression should be applied to the output PDF file.
Compression will be applied by default unless explicitly disabled.

`compress`
:   False to disable PDF compression.

------------------------------------------------------------------------

<a name="setpdftitle"></a>
```php
public function setPDFTitle($pdfTitle)
```

Specify the document title for PDF metadata.

------------------------------------------------------------------------

<a name="setpdfsubject"></a>
```php
public function setPDFSubject($pdfSubject)
```

Specify the document subject for PDF metadata.

------------------------------------------------------------------------

<a name="setpdfauthor"></a>
```php
public function setPDFAuthor($pdfAuthor)
```

Specify the document author for PDF metadata.

------------------------------------------------------------------------

<a name="setpdfkeywords"></a>
```php
public function setPDFKeywords($pdfKeywords)
```

Specify the document keywords for PDF metadata.

------------------------------------------------------------------------

<a name="setpdfcreator"></a>
```php
public function setPDFCreator($pdfCreator)
```

Specify the document creator for PDF metadata.

------------------------------------------------------------------------

<a name="setencrypt"></a>
```php
public function setEncrypt($encrypt)
```

Specify whether encryption should be applied to the output PDF file.
Encryption will not be applied by default unless explicitly enabled.

`encrypt`
:   True to enable PDF encryption.

------------------------------------------------------------------------

<a name="setencryptinfo"></a>
```php
public function setEncryptInfo($keyBits, $userPassword, $ownerPassword,
        $disallowPrint = false, $disallowModify = false,
        $disallowCopy = false, $disallowAnnotate = false)
```

Set the parameters used for PDF encryption. Calling this method will
also enable encryption, equivalent to calling `setEncrypt(true)`. It
should be called before calling a convert method for encryption
information to be applied.

`keyBits`
:   The size of the encryption key in bits (must be 40 or 128).

`userPassword` 
:   The user password for the PDF file (may be empty).

`ownerPassword`
:   The owner password for the PDF file (may be empty).

`disallowPrint`
:   True to disallow printing of the PDF file.

`disallowModify`
:   True to disallow modification of the PDF file.

`disallowCopy`
:   True to disallow copying from the PDF file.

`disallowAnnotate`
:   True to disallow annotation of the PDF file.

------------------------------------------------------------------------

<a name="setoptions"></a>
```php
public function setOptions($options)
```

Set other options.

`options`
:   Other options to set.

------------------------------------------------------------------------

Copyright © 2005-2020 YesLogic Pty. Ltd.
