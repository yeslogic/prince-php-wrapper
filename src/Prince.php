<?php

/**
 * PHP wrapper class for Prince HTML to PDF formatter.
 *
 * @package   Prince
 * @author    Michael Day <mikeday@yeslogic.com>
 * @copyright 2005-2023 YesLogic Pty. Ltd.
 * @license   MIT
 * @version   1.5.0
 * @link      https://www.princexml.com
 */

namespace Prince;

use Exception;

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
    private $iframes;
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
    private $pdfScript;
    private $pdfEventScripts;
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
    private $pdfForms;
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

    // Advanced options.
    private $failDroppedContent;
    private $failMissingResources;
    private $failStrippedTransparency;
    private $failMissingGlyphs;
    private $failPdfProfileError;
    private $failPdfTagError;
    private $failInvalidLicense;

    // Additional options.
    private $options;

    /**
     * Constructor for Prince.
     *
     * @param string $exePath The path of the Prince executable. For example, this
     *                        may be `C:\Program Files\Prince\engine\bin\prince.exe`
     *                        on Windows or `/usr/bin/prince` on Linux. Throws an
     *                        exception if the executable does not exist, or if the
     *                        file does not have execute permissions.
     * @return self
     */
    public function __construct($exePath)
    {
        if (!is_file($exePath)) {
            throw new Exception("Prince could not be found at $exePath");
        }
        if (!is_executable($exePath)) {
            throw new Exception('Prince does not have execute permissions');
        }

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
        $this->iframes = false;
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
        $this->pdfScript = '';
        $this->pdfEventScripts = array();
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
        $this->pdfForms = false;
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

        // Advanced options.
        $this->failDroppedContent = false;
        $this->failMissingResources = false;
        $this->failStrippedTransparency = false;
        $this->failMissingGlyphs = false;
        $this->failPdfProfileError = false;
        $this->failPdfTagError = false;
        $this->failInvalidLicense = false;

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
    public function convertFile($inputPath, &$msgs = array(), &$dats = array())
    {
        return $this->convert_file($inputPath, $msgs, $dats);
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
    public function convertFileToFile(
        $inputPath,
        $pdfPath,
        &$msgs = array(),
        &$dats = array()
    ) {
        return $this->convert_file_to_file($inputPath, $pdfPath, $msgs, $dats);
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
    public function convertMultipleFiles(
        $inputPaths,
        $pdfPath,
        &$msgs = array(),
        &$dats = array()
    ) {
        return $this->convert_multiple_files($inputPaths, $pdfPath, $msgs, $dats);
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
    public function convertMultipleFilesToPassthru(
        $inputPaths,
        &$msgs = array(),
        &$dats = array()
    ) {
        return $this->convert_multiple_files_to_passthru($inputPaths, $msgs, $dats);
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
    public function convertFileToPassthru(
        $inputPath,
        &$msgs = array(),
        &$dats = array()
    ) {
        return $this->convert_file_to_passthru($inputPath, $msgs, $dats);
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
    public function convertStringToPassthru(
        $inputString,
        &$msgs = array(),
        &$dats = array()
    ) {
        return $this->convert_string_to_passthru($inputString, $msgs, $dats);
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
    public function convertStringToFile(
        $inputString,
        $pdfPath,
        &$msgs = array(),
        &$dats = array()
    ) {
        return $this->convert_string_to_file($inputString, $pdfPath, $msgs, $dats);
    }

    /* RASTERIZATION METHODS **************************************************/

    /**
     * Rasterize an XML or HTML file.
     *
     * @param string $inputPath The filename of the input XML or HTML document.
     * @param string $rasterPath A template string from which the raster files
     *                           will be named (e.g. "page_%02d.png" will cause
     *                           Prince to generate page_01.png, page_02.png,
     *                           ..., page_10.png etc.).
     * @param array $msgs An optional array in which to return error and warning
     *                    messages.
     * @param array $dats An optional array in which to return data messages.
     * @return bool `true` if the input was successfully rasterized.
     */
    public function rasterizeFile(
        $inputPath,
        $rasterPath,
        &$msgs = array(),
        &$dats = array()
    ) {
        return $this->rasterizeMultipleFiles(
            array($inputPath),
            $rasterPath,
            $msgs,
            $dats
        );
    }

    /**
     * Rasterize multiple XML or HTML files.
     *
     * @param array $inputPaths An array of the input XML or HTML documents.
     * @param string $rasterPath A template string from which the raster files
     *                           will be named (e.g. "page_%02d.png" will cause
     *                           Prince to generate page_01.png, page_02.png,
     *                           ..., page_10.png etc.).
     * @param array $msgs An optional array in which to return error and warning
     *                    messages.
     * @param array $dats An optional array in which to return data messages.
     * @return bool `true` if the input was successfully rasterized.
     */
    public function rasterizeMultipleFiles(
        $inputPaths,
        $rasterPath,
        &$msgs = array(),
        &$dats = array()
    ) {
        $pathAndArgs = $this->getCommandLine();

        foreach ($inputPaths as $inputPath) {
            $pathAndArgs .= self::cmdArg($inputPath);
        }

        $pathAndArgs .= self::cmdArg('--raster-output', $rasterPath);

        return $this->fileToFile($pathAndArgs, $msgs, $dats);
    }

    /**
     * Rasterize an XML or HTML file, which will be passed through to the output
     * buffer of the current PHP page.
     *
     * @param string $inputPath The filename of the input XML or HTML document.
     * @param array $msgs An optional array in which to return error and warning
     *                    messages.
     * @param array $dats An optional array in which to return data messages.
     * @return bool `true` if the input was successfully rasterized.
     */
    public function rasterizeFileToPassthru(
        $inputPath,
        &$msgs = array(),
        &$dats = array()
    ) {
        return $this->rasterizeMultipleFilesToPassthru(
            array($inputPath),
            $msgs,
            $dats
        );
    }

    /**
     * Rasterize multiple XML or HTML files, which will be passed through to the
     * output buffer of the current PHP page.
     *
     * @param array $inputPaths An array of the input XML or HTML documents.
     * @param array $msgs An optional array in which to return error and warning
     *                    messages.
     * @param array $dats An optional array in which to return data messages.
     * @return bool `true` if the input was successfully rasterized.
     */
    public function rasterizeMultipleFilesToPassthru(
        $inputPaths,
        &$msgs = array(),
        &$dats = array()
    ) {
        if ($this->rasterPage < 1) {
            throw new Exception('rasterPage has to be set to a value of > 0');
        }
        if ($this->rasterFormat == 'auto') {
            throw new Exception('rasterFormat has to be set to "jpeg" or "png"');
        }

        $pathAndArgs = $this->getCommandLine('buffered');

        foreach ($inputPaths as $inputPath) {
            $pathAndArgs .= self::cmdArg($inputPath);
        }

        $pathAndArgs .= self::cmdArg('--raster-output', '-');

        return $this->fileToPassthru($pathAndArgs, $msgs, $dats);
    }

    /**
     * Rasterize an XML or HTML string, which will be passed through to the
     * output buffer of the current PHP page.
     *
     * @param string $inputString A string containing an XML or HTML document.
     * @param array $msgs An optional array in which to return error and warning
     *                    messages.
     * @param array $dats An optional array in which to return data messages.
     * @return bool `true` if the input was successfully rasterized.
     */
    public function rasterizeStringToPassthru(
        $inputString,
        &$msgs = array(),
        &$dats = array()
    ) {
        if ($this->rasterPage < 1) {
            throw new Exception('rasterPage has to be set to a value of > 0');
        }
        if ($this->rasterFormat == 'auto') {
            throw new Exception('rasterFormat has to be set to "jpeg" or "png"');
        }

        $pathAndArgs = $this->getCommandLine('buffered');

        $pathAndArgs .= self::cmdArg('--raster-output', '-');
        $pathAndArgs .= self::cmdArg('-');

        return $this->stringToPassthru($pathAndArgs, $inputString, $msgs, $dats);
    }

    /**
     * Rasterize an XML or HTML string.
     *
     * @param string $inputString A string containing an XML or HTML document.
     * @param string $rasterPath A template string from which the raster files
     *                           will be named (e.g. "page_%02d.png" will cause
     *                           Prince to generate page_01.png, page_02.png,
     *                           ..., page_10.png etc.).
     * @param array $msgs An optional array in which to return error and warning
     *                    messages.
     * @param array $dats An optional array in which to return data messages.
     * @return bool `true` if the input was successfully rasterized.
     */
    public function rasterizeStringToFile(
        $inputString,
        $rasterPath,
        &$msgs = array(),
        &$dats = array()
    ) {
        $pathAndArgs = $this->getCommandLine();

        $pathAndArgs .= self::cmdArg('--raster-output', $rasterPath);
        $pathAndArgs .= self::cmdArg('-');

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
            $this->inputType = 'html';
        } else {
            $this->inputType = 'xml';
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
        $this->remaps .= self::cmdArg('--remap', "$url=$dir");
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
     * Specify whether to enable HTML iframes.
     *
     * @param bool $iframes `true` to enable HTML iframes. Default value is `false`.
     * @return void
     */
    public function setIframes($iframes)
    {
        $this->iframes = $iframes;
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
        $this->authUser = $authUser;
    }

    /**
     * Specify password for HTTP authentication.
     *
     * @param string $authPassword The password for HTTP authentication.
     * @return void
     */
    public function setAuthPassword($authPassword)
    {
        $this->authPassword = $authPassword;
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
        $this->cookies .= self::cmdArg('--cookie', $cookie);
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
        $this->scripts .= self::cmdArg('--script', $jsPath);
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
        $this->styleSheets .= self::cmdArg('--style', $cssPath);
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
     * Include an AcroJS script to run when the PDF is opened.
     *
     * @param string $pdfScript The filename or URL of the AcroJS script.
     * @return void
     */
    public function setPdfScript($pdfScript)
    {
        $this->pdfScript = $pdfScript;
    }

    /**
     * Include an AcroJS script to run on a specific event.
     *
     * @param string $event Can take a value of:
     *                      `"will-close"`,
     *                      `"will-save"`,
     *                      `"did-save"`,
     *                      `"will-print"`,
     *                      `"did-print"`.
     * @param string $script The filename or URL of the AcroJS script.
     * @return void
     */
    public function addPdfEventScript($event, $script)
    {
        $valid = array(
            'will-close',
            'will-save',
            'did-save',
            'will-print',
            'did-print'
        );
        $lower = strtolower($event);

        if (in_array($lower, $valid)) {
            $this->pdfEventScripts[$lower] = $script;
        } else {
            throw new Exception('invalid event value');
        }
    }

    /**
     * Clear all of the AcroJS event scripts.
     *
     * @return void
     */
    public function clearPdfEventScripts()
    {
        $this->pdfEventScripts = array();
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
        $this->fileAttachments .= self::cmdArg('--attach', $filePath);
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
     * Specify whether to enable PDF forms by default.
     *
     * @param bool $pdfForms `true` to enable PDF forms by default. Default
     *                       value is `false`.
     * @return void
     */
    public function setPdfForms($pdfForms)
    {
        $this->pdfForms = $pdfForms;
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
                ' (must be 40 or 128)');
        }

        $this->encrypt = true;

        $this->encryptInfo = self::cmdArg('--key-bits', $keyBits);
        if ($userPassword != '') {
            $this->encryptInfo .= self::cmdArg('--user-password', $userPassword);
        }
        if ($ownerPassword != '') {
            $this->encryptInfo .= self::cmdArg('--owner-password', $ownerPassword);
        }

        if ($disallowPrint) {
            $this->encryptInfo .= self::cmdArg('--disallow-print');
        }

        if ($disallowModify) {
            $this->encryptInfo .= self::cmdArg('--disallow-modify');
        }

        if ($disallowCopy) {
            $this->encryptInfo .= self::cmdArg('--disallow-copy');
        }

        if ($disallowAnnotate) {
            $this->encryptInfo .= self::cmdArg('--disallow-annotate');
        }

        if ($allowCopyForAccessibility) {
            $this->encryptInfo .= self::cmdArg('--allow-copy-for-accessibility');
        }

        if ($allowAssembly) {
            $this->encryptInfo .= self::cmdArg('--allow-assembly');
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

    /* ADVANCED OPTIONS *******************************************************/

    /**
     * Fail-safe option that aborts the creation of a PDF if any content is
     * dropped.
     *
     * @param bool $failDroppedContent `true` to enable fail-safe option.
     *                                 Default value is `false.
     * @return void
     */
    public function setFailDroppedContent($failDroppedContent)
    {
        $this->failDroppedContent = $failDroppedContent;
    }

    /**
     * Fail-safe option that aborts the creation of a PDF if any resources
     * cannot be loaded.
     *
     * @param bool $failMissingResources `true` to enable fail-safe option.
     *                                   Default value is `false.
     * @return void
     */
    public function setFailMissingResources($failMissingResources)
    {
        $this->failMissingResources = $failMissingResources;
    }

    /**
     * Fail-safe option that aborts the creation of a PDF if transparent
     * images are used with a PDF profile that does not support opacity.
     *
     * @param bool $failStrippedTransparency `true` to enable fail-safe option.
     *                                       Default value is `false.
     * @return void
     */
    public function setFailStrippedTransparency($failStrippedTransparency)
    {
        $this->failStrippedTransparency = $failStrippedTransparency;
    }

    /**
     * Fail-safe option that aborts the creation of a PDF if glyphs cannot
     * be found for any characters.
     *
     * @param bool $failMissingGlyphs `true` to enable fail-safe option.
     *                                 Default value is `false.
     * @return void
     */
    public function setFailMissingGlyphs($failMissingGlyphs)
    {
        $this->failMissingGlyphs = $failMissingGlyphs;
    }

    /**
     * Fail-safe option that aborts the creation of a PDF if there are
     * problems complying with the specified PDF profile.
     *
     * @param bool $failPdfProfileError `true` to enable fail-safe option.
     *                                  Default value is `false.
     * @return void
     */
    public function setFailPdfProfileError($failPdfProfileError)
    {
        $this->failPdfProfileError = $failPdfProfileError;
    }

    /**
     * Fail-safe option that aborts the creation of a PDF if there are
     * problems tagging the PDF for accessibility.
     *
     * @param bool $failPdfTagError `true` to enable fail-safe option.
     *                              Default value is `false.
     * @return void
     */
    public function setFailPdfTagError($failPdfTagError)
    {
        $this->failPdfTagError = $failPdfTagError;
    }

    /**
     * Fail-safe option that aborts the creation of a PDF if the Prince
     * license is invalid or not readable.
     *
     * @param bool $failInvalidLicense `true` to enable fail-safe option.
     *                                 Default value is `false.
     * @return void
     */
    public function setFailInvalidLicense($failInvalidLicense)
    {
        $this->failInvalidLicense = $failInvalidLicense;
    }

    /**
     * Enables/disables all fail-safe options.
     *
     * @param bool $failSafe `true` to enable all fail-safe options.
     * @return void
     */
    public function setFailSafe($failSafe)
    {
        $this->failDroppedContent = $failSafe;
        $this->failMissingResources = $failSafe;
        $this->failStrippedTransparency = $failSafe;
        $this->failMissingGlyphs = $failSafe;
        $this->failPdfProfileError = $failSafe;
        $this->failPdfTagError = $failSafe;
        $this->failInvalidLicense = $failSafe;
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

    private function getCommandLine($logType = 'normal')
    {
        $cmdline = self::escape($this->exePath, true, true) . ' ';

        $cmdline .= self::cmdArg('--structured-log', $logType);

        // Logging options.
        if ($this->verbose) {
            $cmdline .= self::cmdArg('--verbose');
        }
        if ($this->debug) {
            $cmdline .= self::cmdArg('--debug');
        }
        if ($this->logFile != '') {
            $cmdline .= self::cmdArg('--log', $this->logFile);
        }
        if ($this->noWarnCssUnknown) {
            $cmdline .= self::cmdArg('--no-warn-css-unknown');
        }
        if ($this->noWarnCssUnsupported) {
            $cmdline .= self::cmdArg('--no-warn-css-unsupported');
        }

        // Input options.
        if ($this->inputType != 'auto') {
            $cmdline .= self::cmdArg('--input', $this->inputType);
        }
        if ($this->baseURL != '') {
            $cmdline .= self::cmdArg('--baseurl', $this->baseURL);
        }
        $cmdline .= $this->remaps;
        if ($this->fileRoot != '') {
            $cmdline .= self::cmdArg('--fileroot', $this->fileRoot);
        }
        if ($this->doXInclude) {
            $cmdline .= self::cmdArg('--xinclude');
        }
        if ($this->xmlExternalEntities) {
            $cmdline .= self::cmdArg('--xml-external-entities');
        }
        if ($this->iframes) {
            $cmdline .= self::cmdArg('--iframes');
        }
        if ($this->noLocalFiles) {
            $cmdline .= self::cmdArg('--no-local-files');
        }

        // Network options.
        if ($this->noNetwork) {
            $cmdline .= self::cmdArg('--no-network');
        }
        if ($this->noRedirects) {
            $cmdline .= self::cmdArg('--no-redirects');
        }
        if ($this->authUser != '') {
            $cmdline .= self::cmdArg('--auth-user', $this->authUser);
        }
        if ($this->authPassword != '') {
            $cmdline .= self::cmdArg('--auth-password', $this->authPassword);
        }
        if ($this->authServer != '') {
            $cmdline .= self::cmdArg('--auth-server', $this->authServer);
        }
        if ($this->authScheme != '') {
            $cmdline .= self::cmdArg('--auth-scheme', $this->authScheme);
        }
        if ($this->authMethods != '') {
            $cmdline .= self::cmdArg('--auth-method', $this->authMethods);
        }
        if ($this->noAuthPreemptive) {
            $cmdline .= self::cmdArg('--no-auth-preemptive');
        }
        if ($this->httpProxy != '') {
            $cmdline .= self::cmdArg('--http-proxy', $this->httpProxy);
        }
        if ($this->httpTimeout > 0) {
            $cmdline .= self::cmdArg('--http-timeout', $this->httpTimeout);
        }
        if ($this->cookie != '') {
            $cmdline .= self::cmdArg('--cookie', $this->cookie);
        }
        $cmdline .= $this->cookies;
        if ($this->cookieJar != '') {
            $cmdline .= self::cmdArg('--cookiejar', $this->cookieJar);
        }
        if ($this->sslCaCert != '') {
            $cmdline .= self::cmdArg('--ssl-cacert', $this->sslCaCert);
        }
        if ($this->sslCaPath != '') {
            $cmdline .= self::cmdArg('--ssl-capath', $this->sslCaPath);
        }
        if ($this->sslCert != '') {
            $cmdline .= self::cmdArg('--ssl-cert', $this->sslCert);
        }
        if ($this->sslCertType != '') {
            $cmdline .= self::cmdArg('--ssl-cert-type', $this->sslCertType);
        }
        if ($this->sslKey != '') {
            $cmdline .= self::cmdArg('--ssl-key', $this->sslKey);
        }
        if ($this->sslKeyType != '') {
            $cmdline .= self::cmdArg('--ssl-key-type', $this->sslKeyType);
        }
        if ($this->sslKeyPassword != '') {
            $cmdline .= self::cmdArg('--ssl-key-password', $this->sslKeyPassword);
        }
        if ($this->sslVersion != '') {
            $cmdline .= self::cmdArg('--ssl-version', $this->sslVersion);
        }
        if ($this->insecure) {
            $cmdline .= self::cmdArg('--insecure');
        }
        if ($this->noParallelDownloads) {
            $cmdline .= self::cmdArg('--no-parallel-downloads');
        }

        // JavaScript options.
        if ($this->javascript) {
            $cmdline .= self::cmdArg('--javascript');
        }
        $cmdline .= $this->scripts;
        if ($this->maxPasses > 0) {
            $cmdline .= self::cmdArg('--max-passes', $this->maxPasses);
        }

        // CSS options.
        $cmdline .= $this->styleSheets;
        if ($this->media != '') {
            $cmdline .= self::cmdArg('--media', $this->media);
        }
        if ($this->pageSize != '') {
            $cmdline .= self::cmdArg('--page-size', $this->pageSize);
        }
        if ($this->pageMargin != '') {
            $cmdline .= self::cmdArg('--page-margin', $this->pageMargin);
        }
        if ($this->noAuthorStyle) {
            $cmdline .= self::cmdArg('--no-author-style');
        }
        if ($this->noDefaultStyle) {
            $cmdline .= self::cmdArg('--no-default-style');
        }

        // PDF output options.
        if ($this->pdfId != '') {
            $cmdline .= self::cmdArg('--pdf-id', $this->pdfId);
        }
        if ($this->pdfScript != '') {
            $cmdline .= self::cmdArg('--pdf-script', $this->pdfScript);
        }
        foreach ($this->pdfEventScripts as $k => $v) {
            $cmdline .= self::cmdArg('--pdf-event-script', $k . ':' . $v);
        }
        if ($this->pdfLang != '') {
            $cmdline .= self::cmdArg('--pdf-lang', $this->pdfLang);
        }
        if ($this->pdfProfile != '') {
            $cmdline .= self::cmdArg('--pdf-profile', $this->pdfProfile);
        }
        if ($this->pdfOutputIntent != '') {
            $cmdline .= self::cmdArg('--pdf-output-intent', $this->pdfOutputIntent);

            if ($this->convertColors) {
                $cmdline .= self::cmdArg('--convert-colors');
            }
        }
        $cmdline .= $this->fileAttachments;
        if ($this->noArtificialFonts) {
            $cmdline .= self::cmdArg('--no-artificial-fonts');
        }
        if (!$this->embedFonts) {
            $cmdline .= self::cmdArg('--no-embed-fonts');
        }
        if (!$this->subsetFonts) {
            $cmdline .= self::cmdArg('--no-subset-fonts');
        }
        if (!$this->systemFonts) {
            $cmdline .= self::cmdArg('--no-system-fonts');
        }
        if ($this->forceIdentityEncoding) {
            $cmdline .= self::cmdArg('--force-identity-encoding');
        }
        if (!$this->compress) {
            $cmdline .= self::cmdArg('--no-compress');
        }
        if ($this->noObjectStreams) {
            $cmdline .= self::cmdArg('--no-object-streams');
        }
        if ($this->fallbackCmykProfile != '') {
            $cmdline .= self::cmdArg('--fallback-cmyk-profile', $this->fallbackCmykProfile);
        }
        if ($this->taggedPdf) {
            $cmdline .= self::cmdArg('--tagged-pdf');
        }
        if ($this->pdfForms) {
            $cmdline .= self::cmdArg('--pdf-forms');
        }
        if ($this->cssDpi > 0) {
            $cmdline .= self::cmdArg('--css-dpi', $this->cssDpi);
        }

        // PDF metadata options.
        if ($this->pdfTitle != '') {
            $cmdline .= self::cmdArg('--pdf-title', $this->pdfTitle);
        }
        if ($this->pdfSubject != '') {
            $cmdline .= self::cmdArg('--pdf-subject', $this->pdfSubject);
        }
        if ($this->pdfAuthor != '') {
            $cmdline .= self::cmdArg('--pdf-author', $this->pdfAuthor);
        }
        if ($this->pdfKeywords != '') {
            $cmdline .= self::cmdArg('--pdf-keywords', $this->pdfKeywords);
        }
        if ($this->pdfCreator != '') {
            $cmdline .= self::cmdArg('--pdf-creator', $this->pdfCreator);
        }
        if ($this->pdfXmp != '') {
            $cmdline .= self::cmdArg('--pdf-xmp', $this->pdfXmp);
        }

        // PDF encryption options.
        if ($this->encrypt) {
            $cmdline .= self::cmdArg('--encrypt');
            $cmdline .= $this->encryptInfo;
        }

        // Raster output options.
        if ($this->rasterFormat != 'auto') {
            $cmdline .= self::cmdArg('--raster-format', $this->rasterFormat);
        }
        if ($this->rasterJpegQuality > -1) {
            $cmdline .= self::cmdArg('--raster-jpeg-quality', $this->rasterJpegQuality);
        }
        if ($this->rasterPage > 0) {
            $cmdline .= self::cmdArg('--raster-pages', $this->rasterPage);
        }
        if ($this->rasterDpi > 0) {
            $cmdline .= self::cmdArg('--raster-dpi', $this->rasterDpi);
        }
        if ($this->rasterThreads > -1) {
            $cmdline .= self::cmdArg('--raster-threads', $this->rasterThreads);
        }
        if ($this->rasterBackground != '') {
            $cmdline .= self::cmdArg('--raster-background', $this->rasterBackground);
        }

        // License options.
        if ($this->licenseFile != '') {
            $cmdline .= self::cmdArg('--license-file', $this->licenseFile);
        }
        if ($this->licenseKey != '') {
            $cmdline .= self::cmdArg('--license-key', $this->licenseKey);
        }

        // Advanced options.
        if ($this->failDroppedContent) {
            $cmdline .= self::cmdArg('--fail-dropped-content');
        }
        if ($this->failMissingResources) {
            $cmdline .= self::cmdArg('--fail-missing-resources');
        }
        if ($this->failStrippedTransparency) {
            $cmdline .= self::cmdArg('--fail-stripped-transparency');
        }
        if ($this->failMissingGlyphs) {
            $cmdline .= self::cmdArg('--fail-missing-glyphs');
        }
        if ($this->failPdfProfileError) {
            $cmdline .= self::cmdArg('--fail-pdf-profile-error');
        }
        if ($this->failPdfTagError) {
            $cmdline .= self::cmdArg('--fail-pdf-tag-error');
        }
        if ($this->failInvalidLicense) {
            $cmdline .= self::cmdArg('--fail-invalid-license');
        }

        // Additional options.
        if ($this->options != '') {
            $cmdline .= self::cmdArg($this->options);
        }

        return $cmdline;
    }

    private function startPrince($pathAndArgs, &$pipes)
    {
        $descriptorSpec = array(
            0 => array('pipe', 'r'),
            1 => array('pipe', 'w'),
            2 => array('pipe', 'w')
        );

        $process = proc_open(
            $pathAndArgs,
            $descriptorSpec,
            $pipes,
            NULL,
            NULL,
            array('bypass_shell' => TRUE)
        );

        if (is_resource($process)) {
            return $process;
        } else {
            throw new Exception("Failed to execute $pathAndArgs");
        }
    }

    private function fileToFile($pathAndArgs, &$msgs, &$dats)
    {
        $process = $this->startPrince($pathAndArgs, $pipes);

        $result = $this->readMessages($pipes[2], $msgs, $dats);

        fclose($pipes[0]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        proc_close($process);

        return ($result == 'success');
    }

    private function stringToFile($pathAndArgs, $inputString, &$msgs, &$dats)
    {
        $process = $this->startPrince($pathAndArgs, $pipes);

        fwrite($pipes[0], $inputString);
        fclose($pipes[0]);
        fclose($pipes[1]);

        $result = $this->readMessages($pipes[2], $msgs, $dats);

        fclose($pipes[2]);

        proc_close($process);

        return ($result == 'success');
    }

    private function fileToPassthru($pathAndArgs, &$msgs, &$dats)
    {
        $process = $this->startPrince($pathAndArgs, $pipes);

        fclose($pipes[0]);
        fpassthru($pipes[1]);
        fclose($pipes[1]);

        $result = $this->readMessages($pipes[2], $msgs, $dats);

        fclose($pipes[2]);

        proc_close($process);

        return ($result == 'success');
    }

    private function stringToPassthru($pathAndArgs, $inputString, &$msgs, &$dats)
    {
        $process = $this->startPrince($pathAndArgs, $pipes);

        fwrite($pipes[0], $inputString);
        fclose($pipes[0]);
        fpassthru($pipes[1]);
        fclose($pipes[1]);

        $result = $this->readMessages($pipes[2], $msgs, $dats);

        fclose($pipes[2]);

        proc_close($process);

        return ($result == 'success');
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
                    self::readNonStructuredMessage($line, $msgs);
                }
            }
        }

        return '';
    }

    private function readNonStructuredMessage($line, &$msgs)
    {
        $princeWrn = 'prince: warning: ';
        $princeErr = 'prince: error: ';

        if (substr($line, 0, strlen($princeWrn)) === $princeWrn) {
            $msgText = substr($line, strlen($princeWrn));
            $msgs[] = array('wrn', '', $msgText);
        } else if (substr($line, 0, strlen($princeErr)) === $princeErr) {
            $msgText = substr($line, strlen($princeErr));
            $msgs[] = array('err', '', $msgText);
        } else {
            // Just treat everything else as debug messages.
            $msgs[] = array('dbg', '', $line);
        }
    }

    private static function cmdArg($key, $value = null)
    {
        $cmd = $key;
        if ($value != null) {
            $cmd .= "=$value";
        }

        return self::escape($cmd) . ' ';
    }

    /**
     * Escapes a string to be used as a shell argument
     *
     * Provides a more robust method on Windows than escapeshellarg. When $meta
     * is true cmd.exe meta-characters will also be escaped. If $module is true,
     * the argument will be treated as the name of the module (executable) to
     * be invoked, with an additional check for edge-case characters that cannot
     * be reliably escaped for cmd.exe. This has no effect if $meta is false.
     *
     * Feel free to copy this function, but please keep the following notice:
     * MIT Licensed (c) John Stevenson <john-stevenson@blueyonder.co.uk>
     * See https://github.com/johnstevenson/winbox-args for more information.
     *
     * @param string $arg The argument to be escaped
     * @param bool $meta Additionally escape cmd.exe meta characters
     * @param bool $module The argument is the module to invoke
     *
     * @return string The escaped argument
     */
    private static function escape($arg, $meta = true, $module = false)
    {
        if (!defined('PHP_WINDOWS_VERSION_BUILD')) {
            // Escape single-quotes and enclose in single-quotes
            return "'" . str_replace("'", "'\\''", $arg) . "'";
        }

        // Check for whitespace or an empty value
        $quote = strpbrk($arg, " \t") !== false || (string) $arg === '';

        // Escape double-quotes and double-up preceding backslashes
        $arg = preg_replace('/(\\\\*)"/', '$1$1\\"', $arg, -1, $dquotes);

        if ($meta) {
            // Check for expansion %..% sequences
            $meta = $dquotes || preg_match('/%[^%]+%/', $arg);

            if (!$meta) {
                // Check for characters that can be escaped in double-quotes
                $quote = $quote || strpbrk($arg, '^&|<>()') !== false;
            } elseif ($module && !$dquotes && $quote) {
                // Caret-escaping a module name with whitespace will split the
                // argument, so just quote it and hope there is no expansion
                $meta = false;
            }
        }

        if ($quote) {
            // Double-up trailing backslashes and enclose in double-quotes
            $arg = '"' . preg_replace('/(\\\\*)$/', '$1$1', $arg) . '"';
        }

        if ($meta) {
            // Caret-escape all meta characters
            $arg = preg_replace('/(["^&|<>()%])/', '^$1', $arg);
        }

        return $arg;
    }

    /* PDF CONVERSION METHODS (DEPRECATED) ************************************/

    /**
     * [DEPRECATED]
     * Convert an XML or HTML file to a PDF file. The name of the output PDF
     * file will be the same as the name of the input file but with an extension
     * of ".pdf".
     *
     * @deprecated 1.2.0 Prefer `convertFile` instead.
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
        $pathAndArgs .= self::cmdArg($inputPath);

        return $this->fileToFile($pathAndArgs, $msgs, $dats);
    }

    /**
     * [DEPRECATED]
     * Convert an XML or HTML file to a PDF file.
     *
     * @deprecated 1.2.0 Prefer `convertFileToFile` instead.
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
        $pathAndArgs .= self::cmdArg($inputPath);
        $pathAndArgs .= self::cmdArg('--output', $pdfPath);

        return $this->fileToFile($pathAndArgs, $msgs, $dats);
    }

    /**
     * [DEPRECATED]
     * Convert multiple XML or HTML files to a PDF file.
     *
     * @deprecated 1.2.0 Prefer `convertMultipleFiles` instead.
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

        foreach ($inputPaths as $inputPath) {
            $pathAndArgs .= self::cmdArg($inputPath);
        }

        $pathAndArgs .= self::cmdArg('--output', $pdfPath);

        return $this->fileToFile($pathAndArgs, $msgs, $dats);
    }

    /**
     * [DEPRECATED]
     * Convert multiple XML or HTML files to a PDF file, which will be passed
     * through to the output buffer of the current PHP page.
     *
     * @deprecated 1.2.0 Prefer `convertMultipleFilesToPassthru` instead.
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
        $pathAndArgs = $this->getCommandLine('buffered');

        foreach ($inputPaths as $inputPath) {
            $pathAndArgs .= self::cmdArg($inputPath);
        }

        $pathAndArgs .= self::cmdArg('--output', '-');

        return $this->fileToPassthru($pathAndArgs, $msgs, $dats);
    }

    /**
     * [DEPRECATED]
     * Convert an XML or HTML file to a PDF file, which will be passed through
     * to the output buffer of the current PHP page.
     *
     * @deprecated 1.2.0 Prefer `convertFileToPassthru` instead.
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
        $pathAndArgs = $this->getCommandLine('buffered');
        $pathAndArgs .= self::cmdArg($inputPath);
        $pathAndArgs .= self::cmdArg('--output', '-');

        return $this->fileToPassthru($pathAndArgs, $msgs, $dats);
    }

    /**
     * [DEPRECATED]
     * Convert an XML or HTML string to a PDF file, which will be passed through
     * to the output buffer of the current PHP page.
     *
     * @deprecated 1.2.0 Prefer `convertStringToPassthru` instead.
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
        $pathAndArgs = $this->getCommandLine('buffered');
        $pathAndArgs .= self::cmdArg('-');

        return $this->stringToPassthru($pathAndArgs, $inputString, $msgs, $dats);
    }

    /**
     * [DEPRECATED]
     * Convert an XML or HTML string to a PDF file.
     *
     * @deprecated 1.2.0 Prefer `convertStringToFile` instead.
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
        $pathAndArgs .= self::cmdArg('--output', $pdfPath);
        $pathAndArgs .= self::cmdArg('-');

        return $this->stringToFile($pathAndArgs, $inputString, $msgs, $dats);
    }
}
