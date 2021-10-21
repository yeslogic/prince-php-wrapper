<?php

/**
 * PHP wrapper class for Prince HTML to PDF formatter.
 *
 * @package   Prince
 * @author    Michael Day <mikeday@yeslogic.com>
 * @copyright 2005-2021 YesLogic Pty. Ltd.
 * @license   MIT
 * @version   1.1.0
 * @link      https://www.princexml.com
 */

namespace Prince;

/**
 * A class that provides an interface to Prince, where each document conversion
 * invokes a new Prince process.
 *
 * When instantiating the `Prince` class, pass in the full path of the Prince
 * executable to the constructor as a string argument. For example, on Linux or
 * macOS:
 *
 * ```
 * use Prince\Prince;
 *
 * $prince = new Prince('/usr/bin/prince');
 * ```
 *
 * On Windows, be sure to specify the path to the `prince.exe` file located
 * within the `engine\bin` subfolder of the Prince installation.
 */
class Prince
{
    private $exePath;

    // Logging options.
    private $verbose;
    private $debug;
    private $logFile;
    private $noWarnCssUnknown;
    private $noWarnCssUnsupported;

    // Input options.
    private $inputType;
    private $baseURL;
    private $remaps;
    private $fileRoot;
    private $doXInclude;
    private $xmlExternalEntities;
    private $noLocalFiles;

    // Network options.
    private $noNetwork;
    private $noRedirects;
    private $authUser;
    private $authPassword;
    private $authServer;
    private $authScheme;
    private $authMethods;
    private $noAuthPreemptive;
    private $httpProxy;
    private $httpTimeout;
    private $cookie; // Deprecated.
    private $cookies;
    private $cookieJar;
    private $sslCaCert;
    private $sslCaPath;
    private $sslCert;
    private $sslCertType;
    private $sslKey;
    private $sslKeyType;
    private $sslKeyPassword;
    private $sslVersion;
    private $insecure;
    private $noParallelDownloads;

    // JavaScript options.
    private $javascript;
    private $scripts;
    private $maxPasses;

    // CSS options.
    private $styleSheets;
    private $media;
    private $pageSize;
    private $pageMargin;
    private $noAuthorStyle;
    private $noDefaultStyle;

    // PDF output options.
    private $pdfId;
    private $pdfLang;
    private $pdfProfile;
    private $pdfOutputIntent;
    private $fileAttachments;
    private $noArtificialFonts;
    private $embedFonts;
    private $subsetFonts;
    private $systemFonts;
    private $forceIdentityEncoding;
    private $compress;
    private $noObjectStreams;
    private $convertColors;
    private $fallbackCmykProfile;
    private $taggedPdf;
    private $cssDpi;

    // PDF metadata options.
    private $pdfTitle;
    private $pdfSubject;
    private $pdfAuthor;
    private $pdfKeywords;
    private $pdfCreator;
    private $pdfXmp;

    // PDF encryption options.
    private $encrypt;
    private $encryptInfo;

    // Raster output options.
    private $rasterFormat;
    private $rasterJpegQuality;
    private $rasterPage;
    private $rasterDpi;
    private $rasterThreads;
    private $rasterBackground;

    // License options.
    private $licenseFile;
    private $licenseKey;

    // Additional options.
    private $options;

    /**
     * Constructor for Prince.
     *
     * @param string $exePath The path of the Prince executable. For example, this
     *                        may be `C:\Program Files\Prince\engine\bin\prince.exe`
     *                        on Windows or `/usr/bin/prince` on Linux.
     * @return self
     */
    public function __construct($exePath)
    {
        $this->exePath = $exePath;

        // Logging options.
        $this->verbose = false;
        $this->debug = false;
        $this->logFile = '';
        $this->noWarnCssUnknown = false;
        $this->noWarnCssUnsupported = false;

        // Input options.
        $this->inputType = 'auto';
        $this->baseURL = '';
        $this->remaps = '';
        $this->fileRoot = '';
        $this->doXInclude = false;
        $this->xmlExternalEntities = false;
        $this->noLocalFiles = false;

        // Network options.
        $this->noNetwork = false;
        $this->noRedirects = false;
        $this->authUser = '';
        $this->authPassword = '';
        $this->authServer = '';
        $this->authScheme = '';
        $this->authMethods = '';
        $this->noAuthPreemptive = false;
        $this->httpProxy = '';
        $this->httpTimeout = 0;
        $this->cookie = ''; // Deprecated.
        $this->cookies = '';
        $this->cookieJar = '';
        $this->sslCaCert = '';
        $this->sslCaPath = '';
        $this->sslCert = '';
        $this->sslCertType = '';
        $this->sslKey = '';
        $this->sslKeyType = '';
        $this->sslKeyPassword = '';
        $this->sslVersion = '';
        $this->insecure = false;
        $this->noParallelDownloads = false;

        // JavaScript options.
        $this->javascript = false;
        $this->scripts = '';
        $this->maxPasses = 0;

        // CSS options.
        $this->styleSheets = '';
        $this->media = '';
        $this->pageSize = '';
        $this->pageMargin = '';
        $this->noAuthorStyle = false;
        $this->noDefaultStyle = false;

        // PDF output options.
        $this->pdfId = '';
        $this->pdfLang = '';
        $this->pdfProfile = '';
        $this->pdfOutputIntent = '';
        $this->fileAttachments = '';
        $this->noArtificialFonts = false;
        $this->embedFonts = true;
        $this->subsetFonts = true;
        $this->systemFonts = true;
        $this->forceIdentityEncoding = false;
        $this->compress = true;
        $this->noObjectStreams = false;
        $this->convertColors = false;
        $this->fallbackCmykProfile = '';
        $this->taggedPdf = false;
        $this->cssDpi = 0;

        // PDF metadata options.
        $this->pdfTitle = '';
        $this->pdfSubject = '';
        $this->pdfAuthor = '';
        $this->pdfKeywords = '';
        $this->pdfCreator = '';
        $this->pdfXmp = '';

        // PDF encryption options.
        $this->encrypt = false;
        $this->encryptInfo = '';

        // Raster output options.
        $this->rasterFormat = 'auto';
        $this->rasterJpegQuality = -1;
        $this->rasterPage = 0;
        $this->rasterDpi = 0;
        $this->rasterThreads = -1;
        $this->rasterBackground = '';

        // License options.
        $this->licenseFile = '';
        $this->licenseKey = '';

        // Additional options.
        $this->options = '';
    }

    /* PDF CONVERSION METHODS *************************************************/

    /**
     * Convert an XML or HTML file to a PDF file. The name of the output PDF
     * file will be the same as the name of the input file but with an extension
     * of ".pdf".
     *
     * @param string $inputPath The filename of the input XML or HTML document.
     * @param array $msgs An optional array in which to return error and warning
     *                    messages.
     * @param array $dats An optional array in which to return data messages.
     * @return bool `true` if a PDF file was generated successfully.
     */
    public function convert_file($inputPath, &$msgs = array(), &$dats = array())
    {
        $pathAndArgs = $this->getCommandLine();
        $pathAndArgs .= '--structured-log=normal ';
        $pathAndArgs .= '"' . $inputPath . '"';

        return $this->fileToFile($pathAndArgs, $msgs, $dats);
    }

    /**
     * Convert an XML or HTML file to a PDF file.
     *
     * @param string $inputPath The filename of the input XML or HTML document.
     * @param string $pdfPath The filename of the output PDF file.
     * @param array $msgs An optional array in which to return error and warning
     *                    messages.
     * @param array $dats An optional array in which to return data messages.
     * @return bool `true` if a PDF file was generated successfully.
     */
    public function convert_file_to_file(
        $inputPath,
        $pdfPath,
        &$msgs = array(),
        &$dats = array()
    ) {
        $pathAndArgs = $this->getCommandLine();
        $pathAndArgs .= '--structured-log=normal ';
        $pathAndArgs .= '"' . $inputPath . '" -o "' . $pdfPath . '"';

        return $this->fileToFile($pathAndArgs, $msgs, $dats);
    }

    /**
     * Convert multiple XML or HTML files to a PDF file.
     *
     * @param array $inputPaths An array of the input XML or HTML documents.
     * @param string $pdfPath The filename of the output PDF file.
     * @param array $msgs An optional array in which to return error and warning
     *                    messages.
     * @param array $dats An optional array in which to return data messages.
     * @return bool `true` if a PDF file was generated successfully.
     */
    public function convert_multiple_files(
        $inputPaths,
        $pdfPath,
        &$msgs = array(),
        &$dats = array()
    ) {
        $pathAndArgs = $this->getCommandLine();
        $pathAndArgs .= '--structured-log=normal ';

        foreach ($inputPaths as $inputPath) {
            $pathAndArgs .= '"' . $inputPath . '" ';
        }

        $pathAndArgs .= '-o "' . $pdfPath . '"';

        return $this->fileToFile($pathAndArgs, $msgs, $dats);
    }

    /**
     * Convert multiple XML or HTML files to a PDF file, which will be passed
     * through to the output buffer of the current PHP page.
     *
     * @param array $inputPaths An array of the input XML or HTML documents.
     * @param array $msgs An optional array in which to return error and warning
     *                    messages.
     * @param array $dats An optional array in which to return data messages.
     * @return bool `true` if a PDF file was generated successfully.
     */
    public function convert_multiple_files_to_passthru(
        $inputPaths,
        &$msgs = array(),
        &$dats = array()
    ) {
        $pathAndArgs = $this->getCommandLine();
        $pathAndArgs .= '--structured-log=buffered ';

        foreach ($inputPaths as $inputPath) {
            $pathAndArgs .= '"' . $inputPath . '" ';
        }

        $pathAndArgs .= '-o -';

        return $this->fileToPassthru($pathAndArgs, $msgs, $dats);
    }

    /**
     * Convert an XML or HTML file to a PDF file, which will be passed through
     * to the output buffer of the current PHP page.
     *
     * @param string $inputPath The filename of the input XML or HTML document.
     * @param array $msgs An optional array in which to return error and warning
     *                    messages.
     * @param array $dats An optional array in which to return data messages.
     * @return bool `true` if a PDF file was generated successfully.
     */
    public function convert_file_to_passthru(
        $inputPath,
        &$msgs = array(),
        &$dats = array()
    ) {
        $pathAndArgs = $this->getCommandLine();
        $pathAndArgs .= '--structured-log=buffered "' . $inputPath . '" -o -';

        return $this->fileToPassthru($pathAndArgs, $msgs, $dats);
    }

    /**
     * Convert an XML or HTML string to a PDF file, which will be passed through
     * to the output buffer of the current PHP page.
     *
     * @param string $inputString A string containing an XML or HTML document.
     * @param array $msgs An optional array in which to return error and warning
     *                    messages.
     * @param array $dats An optional array in which to return data messages.
     * @return bool `true` if a PDF file was generated successfully.
     */
    public function convert_string_to_passthru(
        $inputString,
        &$msgs = array(),
        &$dats = array()
    ) {
        $pathAndArgs = $this->getCommandLine();
        $pathAndArgs .= '--structured-log=buffered -';

        return $this->stringToPassthru($pathAndArgs, $inputString, $msgs, $dats);
    }

    /**
     * Convert an XML or HTML string to a PDF file.
     *
     * @param string $inputString A string containing an XML or HTML document.
     * @param string $pdfPath The filename of the output PDF file.
     * @param array $msgs An optional array in which to return error and warning
     *                    messages.
     * @param array $dats An optional array in which to return data messages.
     * @return bool `true` if a PDF file was generated successfully.
     */
    public function convert_string_to_file(
        $inputString,
        $pdfPath,
        &$msgs = array(),
        &$dats = array()
    ) {
        $pathAndArgs = $this->getCommandLine();
        $pathAndArgs .= '--structured-log=normal ';
        $pathAndArgs .= ' - -o "' . $pdfPath . '"';

        return $this->stringToFile($pathAndArgs, $inputString, $msgs, $dats);
    }

    /* LOGGING OPTIONS ********************************************************/

    /**
     * Specify whether to log informative messages.
     *
     * @param bool $verbose `true` to enable verbose logging. Default value is
     *                      `false`.
     * @return void
     */
    public function setVerbose($verbose)
    {
        $this->verbose = $verbose;
    }

    /**
     * Specify whether to log debug messages.
     *
     * @param bool $debug `true` to enable debug logging. Default value is `false`.
     * @return void
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
    }

    /**
     * Specify a file that Prince should use to log error/warning messages.
     *
     * @param string $logFile The filename that Prince should use to log
     *                        error/warning messages, or `''` to disable logging.
     * @return void
     */
    public function setLog($logFile)
    {
        $this->logFile = $logFile;
    }

    /**
     * Specify whether to warn about unknown CSS features.
     *
     * @param bool $noWarnCssUnknown `true` to disable warnings. Default value
     *                               is `false`.
     * @return void
     */
    public function setNoWarnCssUnknown($noWarnCssUnknown)
    {
        $this->noWarnCssUnknown = $noWarnCssUnknown;
    }

    /**
     * Specify whether to warn about unsupported CSS features.
     *
     * @param bool $noWarnCssUnsupported `true` to disable warnings. Default
     *                                   value is `false`.
     * @return void
     */
    public function setNoWarnCssUnsupported($noWarnCssUnsupported)
    {
        $this->noWarnCssUnsupported = $noWarnCssUnsupported;
    }

    /**
     * Specify whether to warn about unknown and unsupported CSS features.
     *
     * @param bool $noWarnCss `true` to disable warnings. Default value is `false`.
     * @return void
     */
    public function setNoWarnCss($noWarnCss)
    {
        $this->noWarnCssUnknown = $noWarnCss;
        $this->noWarnCssUnsupported = $noWarnCss;
    }

    /* INPUT OPTIONS **********************************************************/

    /**
     * Specify the input type of the document.
     *
     * @param string $inputType Can take a value of: `"xml"`, `"html"`, `"auto"`.
     * @return void
     */
    public function setInputType($inputType)
    {
        $valid = array('xml', 'html', 'auto');
        $lower = strtolower($inputType);

        $this->inputType = in_array($lower, $valid) ? $lower : 'auto';
    }

    /**
     * Specify whether documents should be parsed as HTML or XML/XHTML.
     *
     * @param bool $html `true` if all documents should be treated as HTML.
     * @return void
     */
    public function setHTML($html)
    {
        if ($html) {
            $this->inputType = "html";
        } else {
            $this->inputType = "xml";
        }
    }

    /**
     * Specify the base URL of the input document.
     *
     * @param string $baseURL The base URL or path of the input document, or `''`.
     * @return void
     */
    public function setBaseURL($baseURL)
    {
        $this->baseURL = $baseURL;
    }

    /**
     * Add a mapping of a URL prefix to a local directory.
     *
     * @param string $url The URL prefix to map.
     * @param string $dir The directory that the URL prefix is mapped to.
     * @return void
     */
    public function addRemap($url, $dir)
    {
        $this->remaps .= '--remap="' . $url . '"="' . $dir . '" ';
    }

    /**
     * Clear all of the remaps.
     *
     * @return void
     */
    public function clearRemaps()
    {
        $this->remaps = '';
    }

    /**
     * Specify the root directory for absolute filenames. This can be used when
     * converting a local file that uses absolute paths to refer to web resources.
     * For example, /images/logo.jpg can be rewritten to /usr/share/images/logo.jpg
     * by specifying "/usr/share" as the root.
     *
     * @param string $fileRoot The path to prepend to absolute filenames.
     * @return void
     */
    public function setFileRoot($fileRoot)
    {
        $this->fileRoot = $fileRoot;
    }

    /**
     * Specify whether XML Inclusions (XInclude) processing should be applied to
     * input documents.
     *
     * @param bool $xinclude `true` to enable XInclude processing. Default
     *                         value is `false`.
     * @return void
     */
    public function setXInclude($xinclude)
    {
        $this->doXInclude = $xinclude;
    }

    /**
     * Specifies whether XML external entities (XXE) should be allowed.
     *
     * @param bool $xmlExternalEntities `true` to enable XXE. Default value is
     *                                  `false`.
     * @return void
     */
    public function setXmlExternalEntities($xmlExternalEntities)
    {
        $this->xmlExternalEntities = $xmlExternalEntities;
    }

    /**
     * Specify whether to disable access to local files.
     *
     * @param bool $noLocalFiles `true` to disable access. Default value is `false`.
     * @return void
     */
    public function setNoLocalFiles($noLocalFiles)
    {
        $this->noLocalFiles = $noLocalFiles;
    }

    /* NETWORK OPTIONS ********************************************************/

    /**
     * Specify whether to disable network access.
     *
     * @param bool $noNetwork `true` to disable network access. Default value is
     *                        `false`.
     * @return void
     */
    public function setNoNetwork($noNetwork)
    {
        $this->noNetwork = $noNetwork;
    }

    /**
     * Specify whether to disable all HTTP and HTTPS redirects.
     *
     * @param bool $noRedirects `true` to disable redirects. Default value is
     *                          `false`.
     * @return void
     */
    public function setNoRedirects($noRedirects)
    {
        $this->noRedirects = $noRedirects;
    }

    /**
     * Specify username for HTTP authentication.
     *
     * @param string $authUser The username for HTTP authentication.
     * @return void
     */
    public function setAuthUser($authUser)
    {
        $this->authUser = $this->cmdlineArgEscape($authUser);
    }

    /**
     * Specify password for HTTP authentication.
     *
     * @param string $authPassword The password for HTTP authentication.
     * @return void
     */
    public function setAuthPassword($authPassword)
    {
        $this->authPassword = $this->cmdlineArgEscape($authPassword);
    }

    /**
     * Only send USER:PASS to this server.
     *
     * @param string $authServer The server to send credentials to
     *                           (e.g.`"localhost:8001"`).
     * @return void
     */
    public function setAuthServer($authServer)
    {
        $this->authServer = $authServer;
    }

    /**
     * Only send USER:PASS for this scheme.
     *
     * @param string $authScheme Can take a value of: `"http"`, `"https"`.
     * @return void
     */
    public function setAuthScheme($authScheme)
    {
        $valid = array('http', 'https');
        $lower = strtolower($authScheme);

        $this->authScheme = in_array($lower, $valid) ? $lower : '';
    }

    /**
     * Specify an HTTP authentication method to enable. This method can be called
     * more than once to add multiple authentication methods.
     *
     * @param string $authMethod Can take a value of: `"basic"`, `"digest"`,
     *                           `"ntlm"`, `"negotiate"`.
     * @return void
     */
    public function addAuthMethod($authMethod)
    {
        $valid = array('basic', 'digest', 'ntlm', 'negotiate');
        $lower = strtolower($authMethod);

        if (in_array($lower, $valid)) {
            if ($this->authMethods != '') {
                $this->authMethods .= ',';
            }
            $this->authMethods .= $lower;
        }
    }

    /**
     * Clear all of the enabled authentication methods.
     *
     * @return void
     */
    public function clearAuthMethods()
    {
        $this->authMethods = '';
    }

    /**
     * [DEPRECATED]
     * Specify HTTP authentication methods.
     *
     * @deprecated 1.2.0 Prefer `addAuthMethod` instead.
     *
     * @param string $authMethod Can take a value of: `"basic"`, `"digest"`,
     *                           `"ntlm"`, `"negotiate"`.
     * @return void
     */
    public function setAuthMethod($authMethod)
    {
        $valid = array('basic', 'digest', 'ntlm', 'negotiate');
        $lower = strtolower($authMethod);

        $this->authMethods = in_array($lower, $valid) ? $lower : '';
    }

    /**
     * Do not authenticate with named servers until asked.
     *
     * @param bool $noAuthPreemptive `true` to disable authentication preemptive.
     *                               Default value is `false`.
     * @return void
     */
    public function setNoAuthPreemptive($noAuthPreemptive)
    {
        $this->noAuthPreemptive = $noAuthPreemptive;
    }

    /**
     * Specify the URL for the HTTP proxy server, if needed.
     *
     * @param string $proxy The URL for the HTTP proxy server.
     * @return void
     */
    public function setHttpProxy($proxy)
    {
        $this->httpProxy = $proxy;
    }

    /**
     * Specify the HTTP timeout in seconds.
     *
     * @param int $timeout The HTTP timeout in seconds. Value must be greater
     *                     than 0. Default value is 60 seconds.
     * @return void
     */
    public function setHttpTimeout($httpTimeout)
    {
        if ($httpTimeout < 1) {
            throw new Exception('invalid httpTimeout value (must be > 0)');
        }
        $this->httpTimeout = $httpTimeout;
    }

    /**
     * Specify a Set-Cookie header value. This method can be called more than
     * once to add multiple header values.
     *
     * @param string $cookie The Set-Cookie header value.
     * @return void
     */
    public function addCookie($cookie)
    {
        $this->cookies .= '--cookie="' . $cookie . '" ';
    }

    /**
     * Clear all cookies.
     *
     * @return void
     */
    public function clearCookies()
    {
        $this->cookies = '';
    }

    /**
     * [DEPRECATED]
     * Specify a Set-Cookie header value.
     *
     * @deprecated 1.2.0 Prefer `addCookie` instead.
     *
     * @param string $cookie The Set-Cookie header value.
     * @return void
     */
    public function setCookie($cookie)
    {
        $this->cookie = $cookie;
    }

    /**
     * Specify a file containing HTTP cookies.
     *
     * @param string $cookieJar The filename of the file containing HTTP cookies.
     * @return void
     */
    public function setCookieJar($cookieJar)
    {
        $this->cookieJar = $cookieJar;
    }

    /**
     * Specify an SSL certificate file.
     *
     * @param string $sslCaCert The filename of the SSL certificate file.
     * @return void
     */
    public function setSslCaCert($sslCaCert)
    {
        $this->sslCaCert = $sslCaCert;
    }

    /**
     * Specify an SSL certificate directory.
     *
     * @param string $sslCaPath The SSL certificate directory.
     * @return void
     */
    public function setSslCaPath($sslCaPath)
    {
        $this->sslCaPath = $sslCaPath;
    }

    /**
     * Specify an SSL client certificate file.
     *
     * @param string $sslCert The filename of the SSL client certificate file.
     * @return void
     */
    public function setSslCert($sslCert)
    {
        $this->sslCert = $sslCert;
    }

    /**
     * Specify the SSL client certificate file type.
     *
     * @param string $sslCertType Can take a value of: "PEM", "DER".
     * @return void
     */
    public function setSslCertType($sslCertType)
    {
        $valid = array('pem', 'der');
        $lower = strtolower($sslCertType);

        $this->sslCertType = in_array($lower, $valid) ? $lower : '';
    }

    /**
     * Specify an SSL private key file.
     *
     * @param string $sslKey The filename of the SSL private key file.
     * @return void
     */
    public function setSslKey($sslKey)
    {
        $this->sslKey = $sslKey;
    }

    /**
     * Specify the SSL private key file type.
     *
     * @param string $sslKeyType Can take a value of: "PEM", "DER".
     * @return void
     */
    public function setSslKeyType($sslKeyType)
    {
        $valid = array('pem', 'der');
        $lower = strtolower($sslKeyType);

        $this->sslKeyType = in_array($lower, $valid) ? $lower : '';
    }

    /**
     * Specify a password for the SSL private key.
     *
     * @param string $sslKeyPassword The password for the SSL private key.
     * @return void
     */
    public function setSslKeyPassword($sslKeyPassword)
    {
        $this->sslKeyPassword = $sslKeyPassword;
    }

    /**
     * Specify an SSL/TLS version to use.
     *
     * @param string $sslVersion Can take a value of:
     *                           `"default"`,
     *                           `"tlsv1"`,
     *                           `"tlsv1.0"`,
     *                           `"tlsv1.1"`,
     *                           `"tlsv1.2"`,
     *                           `"tlsv1.3"`.
     * @return void
     */
    public function setSslVersion($sslVersion)
    {
        $valid = array(
            'default',
            'tlsv1',
            'tlsv1.0',
            'tlsv1.1',
            'tlsv1.2',
            'tlsv1.3'
        );
        $lower = strtolower($sslVersion);

        $this->sslVersion = in_array($lower, $valid) ? $lower : '';
    }

    /**
     * Specify whether to disable SSL verification.
     *
     * @param bool $insecure `true` to disable SSL verification (not recommended).
     *                       Default value is `false`.
     * @return void
     */
    public function setInsecure($insecure)
    {
        $this->insecure = $insecure;
    }

    /**
     * Specify whether to disable parallel downloads.
     *
     * @param bool $noParallelDownloads `true` to disable parallel downloads.
     *                                  Default value is `false`.
     * @return void
     */
    public function setNoParallelDownloads($noParallelDownloads)
    {
        $this->noParallelDownloads = $noParallelDownloads;
    }

    /* JAVASCRIPT OPTIONS *****************************************************/

    /**
     * Specify whether JavaScript found in documents should be run.
     *
     * @param bool $js `true` if JavaScript should be run. Default value is `false`.
     * @return void
     */
    public function setJavaScript($js)
    {
        $this->javascript = $js;
    }

    /**
     * Add a JavaScript script that will be run before conversion.
     *
     * @param string $jsPath The filename of the script.
     * @return void
     */
    public function addScript($jsPath)
    {
        $this->scripts .= '--script "' . $jsPath . '" ';
    }

    /**
     * Clear all of the scripts.
     *
     * @return void
     */
    public function clearScripts()
    {
        $this->scripts = '';
    }

    /**
     * Specify the maximum number of consequent layout passes.
     *
     * @param int $maxPasses The maximum number of passes. Value must be greater
     *                       than 0. Default value is unlimited passes.
     * @return void
     */
    public function setMaxPasses($maxPasses)
    {
        if ($maxPasses < 1) {
            throw new Exception('invalid maxPasses value (must be > 0)');
        }
        $this->maxPasses = $maxPasses;
    }

    /* CSS OPTIONS ************************************************************/

    /**
     * Add a CSS style sheet that will be applied to each document.
     *
     * @param string $cssPath The filename of the CSS style sheet.
     * @return void
     */
    public function addStyleSheet($cssPath)
    {
        $this->styleSheets .= '-s "' . $cssPath . '" ';
    }

    /**
     * Clear all of the CSS style sheets.
     *
     * @return void
     */
    public function clearStyleSheets()
    {
        $this->styleSheets = '';
    }

    /**
     * Specify the media type.
     *
     * @param string $media The media type (e.g. `"print"`, `"screen"`).
     * @return void
     */
    public function setMedia($media)
    {
        $this->media = $media;
    }

    /**
     * Specify the page size.
     *
     * @param string $pageSize The page size to use (e.g. `"A4"`).
     * @return void
     */
    public function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;
    }

    /**
     * Specify the page margin.
     *
     * @param string $pageMargin The page margin to use (e.g. `"20mm"`).
     * @return void
     */
    public function setPageMargin($pageMargin)
    {
        $this->pageMargin = $pageMargin;
    }

    /**
     * Specify whether to ignore author style sheets.
     *
     * @param bool $noAuthorStyle `true` to ignore author style sheets. Default
     *                            value is `false`.
     * @return void
     */
    public function setNoAuthorStyle($noAuthorStyle)
    {
        $this->noAuthorStyle = $noAuthorStyle;
    }

    /**
     * Specify whether to ignore default style sheets.
     *
     * @param bool $noDefaultStyle `true` to ignore default style sheets.
     *                             Default value is `false`.
     * @return void
     */
    public function setNoDefaultStyle($noDefaultStyle)
    {
        $this->noDefaultStyle = $noDefaultStyle;
    }

    /* PDF OUTPUT OPTIONS *****************************************************/

    /**
     * Specify the PDF ID to use.
     *
     * @param string $pdfId The PDF ID to use.
     * @return void
     */
    public function setPdfId($pdfId)
    {
        $this->pdfId = $pdfId;
    }

    /**
     * Specify the PDF document's Lang entry in the document catalog.
     *
     * @param string $pdfLang The PDF document's lang entry.
     * @return void
     */
    public function setPdfLang($pdfLang)
    {
        $this->pdfLang = $pdfLang;
    }

    /**
     * Specify the PDF profile to use.
     *
     * @param string $pdfProfile Can take a value of:
     *                           `"PDF/A-1a"`,
     *                           `"PDF/A-1a+PDF/UA-1"`,
     *                           `"PDF/A-1b"`,
     *                           `"PDF/A-2a"`,
     *                           `"PDF/A-2a+PDF/UA-1"`,
     *                           `"PDF/A-2b"`,
     *                           `"PDF/A-3a"`,
     *                           `"PDF/A-3a+PDF/UA-1"`,
     *                           `"PDF/A-3b"`,
     *                           `"PDF/UA-1"`,
     *                           `"PDF/X-1a:2001"`,
     *                           `"PDF/X-1a:2003"`,
     *                           `"PDF/X-3:2002"`,
     *                           `"PDF/X-3:2003"`,
     *                           `"PDF/X-4"`.
     * @return void
     */
    public function setPDFProfile($pdfProfile)
    {
        $valid = array(
            'pdf/a-1a',
            'pdf/a-1a+pdf/ua-1',
            'pdf/a-1b',
            'pdf/a-2a',
            'pdf/a-2a+pdf/ua-1',
            'pdf/a-2b',
            'pdf/a-3a',
            'pdf/a-3a+pdf/ua-1',
            'pdf/a-3b',
            'pdf/ua-1',
            'pdf/x-1a:2001',
            'pdf/x-1a:2003',
            'pdf/x-3:2002',
            'pdf/x-3:2003',
            'pdf/x-4'
        );
        $lower = strtolower($pdfProfile);

        $this->pdfProfile = in_array($lower, $valid) ? $lower : '';
    }

    /**
     * Specify the ICC profile to use. Also, optionally specify whether to
     * convert colors to output intent color space.
     *
     * @param string $pdfOutputIntent The ICC profile.
     * @param bool $convertColors `true` to convert colors to output intent
     *                            color space. Default value is `false`.
     * @return void
     */
    public function setPDFOutputIntent($pdfOutputIntent, $convertColors = false)
    {
        $this->pdfOutputIntent = $pdfOutputIntent;
        $this->convertColors = $convertColors;
    }

    /**
     * Add a file attachment that will be attached to the PDF file.
     *
     * @param string $filePath The filename of the file attachment.
     * @return void
     */
    public function addFileAttachment($filePath)
    {
        $this->fileAttachments .= '--attach=' . '"' . $filePath .  '" ';
    }

    /**
     * Clear all of the file attachments.
     *
     * @return void
     */
    public function clearFileAttachments()
    {
        $this->fileAttachments = '';
    }

    /**
     * Specify whether artificial bold/italic fonts should be generated if
     * necessary.
     *
     * @param bool $noArtificialFonts `true` to disable artificial bold/italic
     *                                fonts. Default value is `false`.
     * @return void
     */
    public function setNoArtificialFonts($noArtificialFonts)
    {
        $this->noArtificialFonts = $noArtificialFonts;
    }

    /**
     * Specify whether fonts should be embedded in the output PDF file.
     *
     * @param bool $embedFonts `false` to disable PDF font embedding. Default
     *                         value is `true`.
     * @return void
     */
    public function setEmbedFonts($embedFonts)
    {
        $this->embedFonts = $embedFonts;
    }

    /**
     * Specify whether embedded fonts should be subset.
     *
     * @param bool $subsetFonts `false` to disable PDF font subsetting. Default
     *                          value is `true`.
     * @return void
     */
    public function setSubsetFonts($subsetFonts)
    {
        $this->subsetFonts = $subsetFonts;
    }

    /**
     * Specify whether system fonts should be enabled.
     *
     * @param bool $systemFonts `false` to disable system fonts. Default value
     *                          is `true`.
     * @return void
     */
    public function setSystemFonts($systemFonts)
    {
        $this->systemFonts = $systemFonts;
    }

    /**
     * Specify whether to use force identity encoding.
     *
     * @param bool $forceIdentityEncoding `true` to force identity encoding.
     *                                    Default value is `false`.
     * @return void
     */
    public function setForceIdentityEncoding($forceIdentityEncoding)
    {
        $this->forceIdentityEncoding = $forceIdentityEncoding;
    }

    /**
     * Specify whether compression should be applied to the output PDF file.
     *
     * @param bool $compress `false` to disable PDF compression. Default value
     *                       is `true`.
     * @return void
     */
    public function setCompress($compress)
    {
        $this->compress = $compress;
    }

    /**
     * Specify whether object streams should be disabled.
     *
     * @param bool $noObjectStreams `true` to disable object streams. Default
     *                              value is `false`.
     * @return void
     */
    public function setNoObjectStreams($noObjectStreams)
    {
        $this->noObjectStreams = $noObjectStreams;
    }

    /**
     * Specify fallback ICC profile for uncalibrated CMYK.
     *
     * @param string $fallbackCmykProfile The fallback ICC profile.
     * @return void
     */
    public function setFallbackCmykProfile($fallbackCmykProfile)
    {
        $this->fallbackCmykProfile = $fallbackCmykProfile;
    }

    /**
     * Specify whether to enable tagged PDF.
     *
     * @param bool $taggedPdf `true` to enable tagged PDF. Default value is `false`.
     * @return void
     */
    public function setTaggedPdf($taggedPdf)
    {
        $this->taggedPdf = $taggedPdf;
    }

    /**
     * Specify the DPI of the "px" units in CSS.
     *
     * @param int $cssDpi The DPI of the "px" units. Value must be greater than
     *                    0. Default value is 96.
     * @return void
     */
    public function setCssDpi($cssDpi)
    {
        if ($cssDpi < 1) {
            throw new Exception('invalid cssDpi value (must be > 0)');
        }
        $this->cssDpi = $cssDpi;
    }

    /* PDF METADATA OPTIONS ***************************************************/

    /**
     * Specify the document title for PDF metadata.
     *
     * @param string $pdfTitle The document title.
     * @return void
     */
    public function setPDFTitle($pdfTitle)
    {
        $this->pdfTitle = $pdfTitle;
    }

    /**
     * Specify the document subject for PDF metadata.

     *
     * @param string $pdfSubject The document subject.
     * @return void
     */
    public function setPDFSubject($pdfSubject)
    {
        $this->pdfSubject = $pdfSubject;
    }

    /**
     * Specify the document author for PDF metadata.
     *
     * @param string $pdfAuthor The document author.
     * @return void
     */
    public function setPDFAuthor($pdfAuthor)
    {
        $this->pdfAuthor = $pdfAuthor;
    }

    /**
     * Specify the document keywords for PDF metadata.
     *
     * @param string $pdfKeywords The document keywords.
     * @return void
     */
    public function setPDFKeywords($pdfKeywords)
    {
        $this->pdfKeywords = $pdfKeywords;
    }

    /**
     * Specify the document creator for PDF metadata.
     *
     * @param string $pdfCreator The document creator.
     * @return void
     */
    public function setPDFCreator($pdfCreator)
    {
        $this->pdfCreator = $pdfCreator;
    }

    /**
     * Specify an XMP file that contains XMP metadata to be included in the
     * output PDF file.
     *
     * @param string $pdfXmp The filename of the XMP file.
     * @return void
     */
    public function setPDFXmp($pdfXmp)
    {
        $this->pdfXmp = $pdfXmp;
    }

    /* PDF ENCRYPTION OPTIONS *************************************************/

    /**
     * Specify whether encryption should be applied to the output PDF file.
     *
     * @param bool $encrypt `true` to enable PDF encryption. Default value is
     *                      `false`.
     * @return void
     */
    public function setEncrypt($encrypt)
    {
        $this->encrypt = $encrypt;
    }

    /**
     * Set the parameters used for PDF encryption. Calling this method will also
     * enable PDF encryption, equivalent to calling setEncrypt(true).
     *
     * @param int $keyBits The size of the encryption key in bits
     *                     (must be 40 or 128).
     * @param string $userPassword The user password for the PDF file.
     * @param string $ownerPassword The owner password for the PDF file.
     * @param bool $disallowPrint `true` to disallow printing of the PDF file.
     *                            Default value is `false`.
     * @param bool $disallowModify `true` to disallow modification of the PDF file.
     *                             Default value is `false`.
     * @param bool $disallowCopy `true` to disallow copying from the PDF file.
     *                           Default value is `false`.
     * @param bool $disallowAnnotate `true` to disallow annotation of the PDF file.
     *                               Default value is `false`.
     * @param bool $allowCopyForAccessibility `true` to allow copying content for
     *                                        accessibility purposes. Default
     *                                        value is `false`.
     * @param bool $allowAssembly `true` to allow the document to be inserted into
     *                            another document or other pages to be added.
     *                            Default value is `false`.
     * @return void
     */
    public function setEncryptInfo(
        $keyBits,
        $userPassword,
        $ownerPassword,
        $disallowPrint = false,
        $disallowModify = false,
        $disallowCopy = false,
        $disallowAnnotate = false,
        $allowCopyForAccessibility = false,
        $allowAssembly = false
    ) {
        if ($keyBits != 40 && $keyBits != 128) {
            throw new Exception("Invalid value for keyBits: $keyBits" .
                " (must be 40 or 128)");
        }

        $this->encrypt = true;

        $this->encryptInfo =
            ' --key-bits ' . $keyBits .
            ' --user-password="' . $this->cmdlineArgEscape($userPassword) .
            '" --owner-password="' . $this->cmdlineArgEscape($ownerPassword) .
            '" ';

        if ($disallowPrint) {
            $this->encryptInfo .= '--disallow-print ';
        }

        if ($disallowModify) {
            $this->encryptInfo .= '--disallow-modify ';
        }

        if ($disallowCopy) {
            $this->encryptInfo .= '--disallow-copy ';
        }

        if ($disallowAnnotate) {
            $this->encryptInfo .= '--disallow-annotate ';
        }

        if ($allowCopyForAccessibility) {
            $this->encryptInfo .= '--allow-copy-for-accessibility ';
        }

        if ($allowAssembly) {
            $this->encryptInfo .= '--allow-assembly ';
        }
    }

    /* RASTER OUTPUT OPTIONS **************************************************/

    /**
     * Specify the format for the raster output.
     *
     * @param string $rasterFormat Can take a value of: `"auto"`, `"png"`, `"jpeg"`.
     * @return void
     */
    public function setRasterFormat($rasterFormat)
    {
        $valid = array('auto', 'png', 'jpeg');
        $lower = strtolower($rasterFormat);

        $this->rasterFormat = in_array($lower, $valid) ? $lower : 'auto';
    }

    /**
     * Specify the level of JPEG compression when generating raster output in
     * JPEG format.
     *
     * @param int $rasterJpegQuality The level of JPEG compression. Valid range
     *                               is between 0 and 100 inclusive. Default
     *                               value is 92 percent.
     * @return void
     */
    public function setRasterJpegQuality($rasterJpegQuality)
    {
        if ($rasterJpegQuality < 0 || $rasterJpegQuality > 100) {
            throw new Exception('invalid rasterJpegQuality value (must be [0, 100])');
        }
        $this->rasterJpegQuality = $rasterJpegQuality;
    }

    /**
     * Specify the page number to be rasterized.
     *
     * @param int $rasterPage The page number to be rasterized. Value must be
     *                        greater than 0. Defaults to rasterizing all pages.
     * @return void
     */
    public function setRasterPage($rasterPage)
    {
        if ($rasterPage < 1) {
            throw new Exception('invalid rasterPage value (must be > 0)');
        }
        $this->rasterPage = $rasterPage;
    }

    /**
     * Specify the resolution of the raster output.
     *
     * @param int $rasterDpi The resolution of the raster output. Value must be
     *                       greater than 0. Default value is 96 dpi.
     * @return void
     */
    public function setRasterDpi($rasterDpi)
    {
        if ($rasterDpi < 1) {
            throw new Exception('invalid rasterDpi value (must be > 0)');
        }
        $this->rasterDpi = $rasterDpi;
    }

    /**
     * Specify the number of threads to use for multi-threaded rasterization.
     *
     * @param int $rasterThreads The number of threads to use. Default value is
     *                           the number of cores and hyperthreads the system
     *                           provides.
     * @return void
     */
    public function setRasterThreads($rasterThreads)
    {
        $this->rasterThreads = $rasterThreads;
    }

    /**
     * Specify the background. Can be used when rasterizing to an image format
     * that supports transparency.
     *
     * @param string $rasterBackground Can take a value of: `"white"`, `"transparent"`.
     * @return void
     */
    public function setRasterBackground($rasterBackground)
    {
        $valid = array('white', 'transparent');
        $lower = strtolower($rasterBackground);

        $this->rasterBackground = in_array($lower, $valid) ? $lower : '';
    }

    /* LICENSE OPTIONS ********************************************************/

    /**
     * Specify the license file.
     *
     * @param string $file The filename of the license file.
     * @return void
     */
    public function setLicenseFile($file)
    {
        $this->licenseFile = $file;
    }

    /**
     * Specify the license key.
     *
     * @param string $key The license key. This is the `<signature>` field in
     *                    the license file.
     * @return void
     */
    public function setLicenseKey($key)
    {
        $this->licenseKey = $key;
    }

    /* ADDITIONAL OPTIONS *****************************************************/

    /**
     * Specify additional Prince command-line options.
     *
     * @param string $options Additional Prince command-line options.
     * @return void
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /* PRIVATE HELPER METHODS *************************************************/

    private function getCommandLine()
    {
        $cmdline = '"' . $this->exePath . '" ';

        // Logging options.
        if ($this->verbose) {
            $cmdline .= '--verbose ';
        }
        if ($this->debug) {
            $cmdline .= '--debug ';
        }
        if ($this->logFile != '') {
            $cmdline .= '--log="' . $this->logFile . '" ';
        }
        if ($this->noWarnCssUnknown) {
            $cmdline .= '--no-warn-css-unknown ';
        }
        if ($this->noWarnCssUnsupported) {
            $cmdline .= '--no-warn-css-unsupported ';
        }

        // Input options.
        if ($this->inputType != 'auto') {
            $cmdline .=  '-i "' . $this->inputType . '" ';
        }
        if ($this->baseURL != '') {
            $cmdline .= '--baseurl="' . $this->baseURL . '" ';
        }
        $cmdline .= $this->remaps;
        if ($this->fileRoot != '') {
            $cmdline .= '--fileroot="' . $this->fileRoot . '" ';
        }
        if ($this->doXInclude) {
            $cmdline .= '--xinclude ';
        }
        if ($this->xmlExternalEntities) {
            $cmdline .= '--xml-external-entities ';
        }
        if ($this->noLocalFiles) {
            $cmdline .= '--no-local-files ';
        }

        // Network options.
        if ($this->noNetwork) {
            $cmdline .= '--no-network ';
        }
        if ($this->noRedirects) {
            $cmdline .= '--no-redirects ';
        }
        if ($this->authUser != '') {
            $cmdline .= '--auth-user="' . $this->cmdlineArgEscape($this->authUser) . '" ';
        }
        if ($this->authPassword != '') {
            $cmdline .= '--auth-password="' . $this->cmdlineArgEscape($this->authPassword) . '" ';
        }
        if ($this->authServer != '') {
            $cmdline .= '--auth-server="' . $this->cmdlineArgEscape($this->authServer) . '" ';
        }
        if ($this->authScheme != '') {
            $cmdline .= '--auth-scheme="' . $this->cmdlineArgEscape($this->authScheme) . '" ';
        }
        if ($this->authMethods != '') {
            $cmdline .=  '--auth-method="' . $this->cmdlineArgEscape($this->authMethods) . '" ';
        }
        if ($this->noAuthPreemptive) {
            $cmdline .= '--no-auth-preemptive ';
        }
        if ($this->httpProxy != '') {
            $cmdline .= '--http-proxy="' . $this->httpProxy . '" ';
        }
        if ($this->httpTimeout > 0) {
            $cmdline .= '--http-timeout="' . $this->httpTimeout . '" ';
        }
        if ($this->cookie != '') {
            $cmdline .= '--cookie="' . $this->cookie . '" ';
        }
        $cmdline .= $this->cookies;
        if ($this->cookieJar != '') {
            $cmdline .= '--cookiejar="' . $this->cookieJar . '" ';
        }
        if ($this->sslCaCert != '') {
            $cmdline .= '--ssl-cacert="' . $this->sslCaCert . '" ';
        }
        if ($this->sslCaPath != '') {
            $cmdline .= '--ssl-capath="' . $this->sslCaPath . '" ';
        }
        if ($this->sslCert != '') {
            $cmdline .= '--ssl-cert="' . $this->sslCert . '" ';
        }
        if ($this->sslCertType != '') {
            $cmdline .= '--ssl-cert-type="' . $this->sslCertType . '" ';
        }
        if ($this->sslKey != '') {
            $cmdline .= '--ssl-key="' . $this->sslKey . '" ';
        }
        if ($this->sslKeyType != '') {
            $cmdline .= '--ssl-key-type="' . $this->sslKeyType . '" ';
        }
        if ($this->sslKeyPassword != '') {
            $cmdline .= '--ssl-key-password="' . $this->cmdlineArgEscape($this->sslKeyPassword) . '" ';
        }
        if ($this->sslVersion != '') {
            $cmdline .= '--ssl-version="' . $this->sslVersion . '" ';
        }
        if ($this->insecure) {
            $cmdline .= '--insecure ';
        }
        if ($this->noParallelDownloads) {
            $cmdline .= '--no-parallel-downloads ';
        }

        // JavaScript options.
        if ($this->javascript) {
            $cmdline .= '--javascript ';
        }
        $cmdline .= $this->scripts;
        if ($this->maxPasses > 0) {
            $cmdline .= '--max-passes="' . $this->maxPasses . '" ';
        }

        // CSS options.
        $cmdline .= $this->styleSheets;
        if ($this->media != '') {
            $cmdline .= '--media="' . $this->cmdlineArgEscape($this->media) . '" ';
        }
        if ($this->pageSize != '') {
            $cmdline .= '--page-size="' . $this->cmdlineArgEscape($this->pageSize) . '" ';
        }
        if ($this->pageMargin != '') {
            $cmdline .= '--page-margin="' . $this->cmdlineArgEscape($this->pageMargin) . '" ';
        }
        if ($this->noAuthorStyle) {
            $cmdline .= '--no-author-style ';
        }
        if ($this->noDefaultStyle) {
            $cmdline .= '--no-default-style ';
        }

        // PDF output options.
        if ($this->pdfId != '') {
            $cmdline .= '--pdf-id="' . $this->cmdlineArgEscape($this->pdfId) . '" ';
        }
        if ($this->pdfLang != '') {
            $cmdline .= '--pdf-lang="' . $this->cmdlineArgEscape($this->pdfLang) . '" ';
        }
        if ($this->pdfProfile != '') {
            $cmdline .= '--pdf-profile="' . $this->cmdlineArgEscape($this->pdfProfile) . '" ';
        }
        if ($this->pdfOutputIntent != '') {
            $cmdline .= '--pdf-output-intent="' . $this->cmdlineArgEscape($this->pdfOutputIntent) . '" ';

            if ($this->convertColors) {
                $cmdline .= '--convert-colors ';
            }
        }
        $cmdline .= $this->fileAttachments;
        if ($this->noArtificialFonts) {
            $cmdline .= '--no-artificial-fonts ';
        }
        if (!$this->embedFonts) {
            $cmdline .= '--no-embed-fonts ';
        }
        if (!$this->subsetFonts) {
            $cmdline .= '--no-subset-fonts ';
        }
        if (!$this->systemFonts) {
            $cmdline .= '--no-system-fonts ';
        }
        if ($this->forceIdentityEncoding) {
            $cmdline .= '--force-identity-encoding ';
        }
        if (!$this->compress) {
            $cmdline .= '--no-compress ';
        }
        if ($this->noObjectStreams) {
            $cmdline .= '--no-object-streams ';
        }
        if ($this->fallbackCmykProfile != '') {
            $cmdline .= '--fallback-cmyk-profile="' . $this->cmdlineArgEscape($this->fallbackCmykProfile) . '" ';
        }
        if ($this->taggedPdf) {
            $cmdline .= '--tagged-pdf ';
        }
        if ($this->cssDpi > 0) {
            $cmdline .= '--css-dpi="' . $this->cssDpi . '" ';
        }

        // PDF metadata options.
        if ($this->pdfTitle != '') {
            $cmdline .= '--pdf-title="' . $this->cmdlineArgEscape($this->pdfTitle) . '" ';
        }
        if ($this->pdfSubject != '') {
            $cmdline .= '--pdf-subject="' . $this->cmdlineArgEscape($this->pdfSubject) . '" ';
        }
        if ($this->pdfAuthor != '') {
            $cmdline .= '--pdf-author="' . $this->cmdlineArgEscape($this->pdfAuthor) . '" ';
        }
        if ($this->pdfKeywords != '') {
            $cmdline .= '--pdf-keywords="' . $this->cmdlineArgEscape($this->pdfKeywords) . '" ';
        }
        if ($this->pdfCreator != '') {
            $cmdline .= '--pdf-creator="' . $this->cmdlineArgEscape($this->pdfCreator) . '" ';
        }
        if ($this->pdfXmp != '') {
            $cmdline .= '--pdf-xmp="' . $this->pdfXmp . '" ';
        }

        // PDF encryption options.
        if ($this->encrypt) {
            $cmdline .= '--encrypt ' . $this->encryptInfo;
        }

        // Raster output options.
        if ($this->rasterFormat != 'auto') {
            $cmdline .= '--raster-format="' . $this->rasterFormat . '" ';
        }
        if ($this->rasterJpegQuality > -1) {
            $cmdline .= '--raster-jpeg-quality="' . $this->rasterJpegQuality . '" ';
        }
        if ($this->rasterPage > 0) {
            $cmdline .= '--raster-pages="' . $this->rasterPage . '" ';
        }
        if ($this->rasterDpi > 0) {
            $cmdline .= '--raster-dpi="' . $this->rasterDpi . '" ';
        }
        if ($this->rasterThreads > -1) {
            $cmdline .= '--raster-threads="' . $this->rasterThreads . '" ';
        }
        if ($this->rasterBackground != '') {
            $cmdline .= '--raster-background="' . $this->rasterBackground . '" ';
        }

        // License options.
        if ($this->licenseFile != '') {
            $cmdline .= '--license-file="' . $this->licenseFile . '" ';
        }

        if ($this->licenseKey != '') {
            $cmdline .= '--license-key="' . $this->licenseKey . '" ';
        }

        // Additional options.
        if ($this->options != '') {
            $cmdline .= $this->cmdlineArgEscape($this->options) . ' ';
        }

        return $cmdline;
    }

    private function fileToFile($pathAndArgs, &$msgs, &$dats)
    {
        $descriptorspec = array(
            0 => array("pipe", "r"),
            1 => array("pipe", "w"),
            2 => array("pipe", "w")
        );

        $process = proc_open(
            $pathAndArgs,
            $descriptorspec,
            $pipes,
            NULL,
            NULL,
            array('bypass_shell' => TRUE)
        );

        if (is_resource($process)) {
            $result = $this->readMessages($pipes[2], $msgs, $dats);

            fclose($pipes[0]);
            fclose($pipes[1]);
            fclose($pipes[2]);

            proc_close($process);

            return ($result == 'success');
        } else {
            throw new Exception("Failed to execute $pathAndArgs");
        }
    }

    private function stringToFile($pathAndArgs, $inputString, &$msgs, &$dats)
    {
        $descriptorspec = array(
            0 => array("pipe", "r"),
            1 => array("pipe", "w"),
            2 => array("pipe", "w")
        );

        $process = proc_open(
            $pathAndArgs,
            $descriptorspec,
            $pipes,
            NULL,
            NULL,
            array('bypass_shell' => TRUE)
        );

        if (is_resource($process)) {
            fwrite($pipes[0], $inputString);
            fclose($pipes[0]);
            fclose($pipes[1]);

            $result = $this->readMessages($pipes[2], $msgs, $dats);

            fclose($pipes[2]);

            proc_close($process);

            return ($result == 'success');
        } else {
            throw new Exception("Failed to execute $pathAndArgs");
        }
    }

    private function fileToPassthru($pathAndArgs, &$msgs, &$dats)
    {
        $descriptorspec = array(
            0 => array("pipe", "r"),
            1 => array("pipe", "w"),
            2 => array("pipe", "w")
        );

        $process = proc_open(
            $pathAndArgs,
            $descriptorspec,
            $pipes,
            NULL,
            NULL,
            array('bypass_shell' => TRUE)
        );

        if (is_resource($process)) {
            fclose($pipes[0]);
            fpassthru($pipes[1]);
            fclose($pipes[1]);

            $result = $this->readMessages($pipes[2], $msgs, $dats);

            fclose($pipes[2]);

            proc_close($process);

            return ($result == 'success');
        } else {
            throw new Exception("Failed to execute $pathAndArgs");
        }
    }

    private function stringToPassthru($pathAndArgs, $inputString, &$msgs, &$dats)
    {
        $descriptorspec = array(
            0 => array("pipe", "r"),
            1 => array("pipe", "w"),
            2 => array("pipe", "w")
        );

        $process = proc_open(
            $pathAndArgs,
            $descriptorspec,
            $pipes,
            NULL,
            NULL,
            array('bypass_shell' => TRUE)
        );

        if (is_resource($process)) {
            fwrite($pipes[0], $inputString);
            fclose($pipes[0]);
            fpassthru($pipes[1]);
            fclose($pipes[1]);

            $result = $this->readMessages($pipes[2], $msgs, $dats);

            fclose($pipes[2]);

            proc_close($process);

            return ($result == 'success');
        } else {
            throw new Exception("Failed to execute $pathAndArgs");
        }
    }

    private function readMessages($pipe, &$msgs, &$dats)
    {
        while (!feof($pipe)) {
            $line = fgets($pipe);

            if ($line != false) {
                $msgtag = substr($line, 0, 4);
                $msgbody = rtrim(substr($line, 4));

                if ($msgtag == 'fin|') {
                    return $msgbody;
                } else if ($msgtag == 'msg|') {
                    $msg = explode('|', $msgbody, 4);

                    // $msg[0] = 'err' | 'wrn' | 'inf'
                    // $msg[1] = filename / line number
                    // $msg[2] = message text, trailing newline stripped

                    $msgs[] = $msg;
                } else if ($msgtag == 'dat|') {
                    $dat = explode('|', $msgbody, 2);

                    $dats[] = $dat;
                } else {
                    // ignore other messages
                }
            }
        }

        return '';
    }

    private function cmdlineArgEscape($argStr)
    {
        return $this->cmdlineArgEscape2($this->cmdlineArgEscape1($argStr));
    }

    // In the input string $argStr, a double quote with zero or more preceding
    // backslash(es) will be replaced with:
    // n*backslash + doublequote => (2*n+1)*backslash + doublequote
    private function cmdlineArgEscape1($argStr)
    {
        // chr(34) is character double quote ( " ),
        // chr(92) is character backslash ( \ ).
        $len = strlen($argStr);

        $outputStr = '';
        $numSlashes = 0;
        $subStrStart = 0;

        for ($i = 0; $i < $len; $i++) {
            if ($argStr[$i] == chr(34)) {
                $numSlashes = 0;
                $j = $i - 1;
                while ($j >= 0) {
                    if ($argStr[$j] == chr(92)) {
                        $numSlashes += 1;
                        $j -= 1;
                    } else {
                        break;
                    }
                }

                $outputStr .= substr(
                    $argStr,
                    $subStrStart,
                    ($i - $numSlashes - $subStrStart)
                );

                for ($k = 0; $k < $numSlashes; $k++) {
                    $outputStr .= chr(92) . chr(92);
                }
                $outputStr .= chr(92) . chr(34);

                $subStrStart = $i + 1;
            }
        }
        $outputStr .= substr($argStr, $subStrStart, ($i - $subStrStart));

        return $outputStr;
    }

    // Double the number of trailing backslash(es):
    // n*trailing backslash => (2*n)*trailing backslash.
    private function cmdlineArgEscape2($argStr)
    {
        // chr(92) is character backslash ( \ ).
        $len = strlen($argStr);

        $numTrailingSlashes = 0;
        for ($i = ($len - 1); $i >= 0; $i--) {
            if ($argStr[$i] == chr(92)) {
                $numTrailingSlashes += 1;
            } else {
                break;
            }
        }

        while ($numTrailingSlashes > 0) {
            $argStr .= chr(92);
            $numTrailingSlashes -= 1;
        }

        return $argStr;
    }
}
