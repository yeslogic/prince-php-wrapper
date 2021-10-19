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
    private $noWarnCss;

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
    private $authUser;
    private $authPassword;
    private $authServer;
    private $authScheme;
    private $authMethod;
    private $noAuthPreemptive;
    private $httpProxy;
    private $httpTimeout;
    private $cookie;
    private $cookieJar;
    private $sslCaCert;
    private $sslCaPath;
    private $sslVersion;
    private $insecure;
    private $noParallelDownloads;

    // JavaScript options.
    private $javascript;
    private $scripts;

    // CSS options.
    private $styleSheets;
    private $media;
    private $pageSize;
    private $pageMargin;
    private $noAuthorStyle;
    private $noDefaultStyle;

    // PDF output options.
    private $pdfProfile;
    private $pdfOutputIntent;
    private $fileAttachments;
    private $noArtificialFonts;
    private $embedFonts;
    private $subsetFonts;
    private $forceIdentityEncoding;
    private $compress;
    private $convertColors;
    private $fallbackCmykProfile;

    // PDF metadata options.
    private $pdfTitle;
    private $pdfSubject;
    private $pdfAuthor;
    private $pdfKeywords;
    private $pdfCreator;

    // PDF encryption options.
    private $encrypt;
    private $encryptInfo;

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
        $this->noWarnCss = false;

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
        $this->authUser = '';
        $this->authPassword = '';
        $this->authServer = '';
        $this->authScheme = '';
        $this->authMethod = '';
        $this->noAuthPreemptive = false;
        $this->httpProxy = '';
        $this->httpTimeout = '';
        $this->cookie = '';
        $this->cookieJar = '';
        $this->sslCaCert = '';
        $this->sslCaPath = '';
        $this->sslVersion = '';
        $this->insecure = false;
        $this->noParallelDownloads = false;

        // JavaScript options.
        $this->javascript = false;
        $this->scripts = '';

        // CSS options.
        $this->styleSheets = '';
        $this->media = '';
        $this->pageSize = '';
        $this->pageMargin = '';
        $this->noAuthorStyle = false;
        $this->noDefaultStyle = false;

        // PDF output options.
        $this->pdfProfile = '';
        $this->pdfOutputIntent = '';
        $this->fileAttachments = '';
        $this->noArtificialFonts = false;
        $this->embedFonts = true;
        $this->subsetFonts = true;
        $this->forceIdentityEncoding = false;
        $this->compress = true;
        $this->convertColors = false;
        $this->fallbackCmykProfile = '';

        // PDF metadata options.
        $this->pdfTitle = '';
        $this->pdfSubject = '';
        $this->pdfAuthor = '';
        $this->pdfKeywords = '';
        $this->pdfCreator = '';

        // PDF encryption options.
        $this->encrypt = false;
        $this->encryptInfo = '';

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
     * @param string $xmlPath The filename of the input XML or HTML document.
     * @param array $msgs An optional array in which to return error and warning
     *                    messages.
     * @param array $dats An optional array in which to return data messages.
     * @return bool `true` if a PDF file was generated successfully.
     */
    public function convert_file($xmlPath, &$msgs = array(), &$dats = array())
    {
        $pathAndArgs = $this->getCommandLine();
        $pathAndArgs .= '--structured-log=normal ';
        $pathAndArgs .= '"' . $xmlPath . '"';

        return $this->convert_internal_file_to_file($pathAndArgs, $msgs, $dats);
    }

    /**
     * Convert an XML or HTML file to a PDF file.
     *
     * @param string $xmlPath The filename of the input XML or HTML document.
     * @param string $pdfPath The filename of the output PDF file.
     * @param array $msgs An optional array in which to return error and warning
     *                    messages.
     * @param array $dats An optional array in which to return data messages.
     * @return bool `true` if a PDF file was generated successfully.
     */
    public function convert_file_to_file(
        $xmlPath,
        $pdfPath,
        &$msgs = array(),
        &$dats = array()
    ) {
        $pathAndArgs = $this->getCommandLine();
        $pathAndArgs .= '--structured-log=normal ';
        $pathAndArgs .= '"' . $xmlPath . '" -o "' . $pdfPath . '"';

        return $this->convert_internal_file_to_file($pathAndArgs, $msgs, $dats);
    }

    /**
     * Convert multiple XML or HTML files to a PDF file.
     *
     * @param array $xmlPaths An array of the input XML or HTML documents.
     * @param string $pdfPath The filename of the output PDF file.
     * @param array $msgs An optional array in which to return error and warning
     *                    messages.
     * @param array $dats An optional array in which to return data messages.
     * @return bool `true` if a PDF file was generated successfully.
     */
    public function convert_multiple_files(
        $xmlPaths,
        $pdfPath,
        &$msgs = array(),
        &$dats = array()
    ) {
        $pathAndArgs = $this->getCommandLine();
        $pathAndArgs .= '--structured-log=normal ';

        foreach ($xmlPaths as $xmlPath) {
            $pathAndArgs .= '"' . $xmlPath . '" ';
        }

        $pathAndArgs .= '-o "' . $pdfPath . '"';

        return $this->convert_internal_file_to_file($pathAndArgs, $msgs, $dats);
    }

    /**
     * Convert multiple XML or HTML files to a PDF file, which will be passed
     * through to the output buffer of the current PHP page.
     *
     * @param array $xmlPaths An array of the input XML or HTML documents.
     * @param array $msgs An optional array in which to return error and warning
     *                    messages.
     * @param array $dats An optional array in which to return data messages.
     * @return bool `true` if a PDF file was generated successfully.
     */
    public function convert_multiple_files_to_passthru(
        $xmlPaths,
        &$msgs = array(),
        &$dats = array()
    ) {
        $pathAndArgs = $this->getCommandLine();
        $pathAndArgs .= '--structured-log=buffered ';

        foreach ($xmlPaths as $xmlPath) {
            $pathAndArgs .= '"' . $xmlPath . '" ';
        }

        $pathAndArgs .= '-o -';

        return $this->convert_internal_file_to_passthru($pathAndArgs, $msgs, $dats);
    }

    /**
     * Convert an XML or HTML file to a PDF file, which will be passed through
     * to the output buffer of the current PHP page.
     *
     * @param string $xmlPath The filename of the input XML or HTML document.
     * @param array $msgs An optional array in which to return error and warning
     *                    messages.
     * @param array $dats An optional array in which to return data messages.
     * @return bool `true` if a PDF file was generated successfully.
     */
    public function convert_file_to_passthru(
        $xmlPath,
        &$msgs = array(),
        &$dats = array()
    ) {
        $pathAndArgs = $this->getCommandLine();
        $pathAndArgs .= '--structured-log=buffered "' . $xmlPath . '" -o -';

        return $this->convert_internal_file_to_passthru($pathAndArgs, $msgs, $dats);
    }

    /**
     * Convert an XML or HTML string to a PDF file, which will be passed through
     * to the output buffer of the current PHP page.
     *
     * @param string $xmlString A string containing an XML or HTML document.
     * @param array $msgs An optional array in which to return error and warning
     *                    messages.
     * @param array $dats An optional array in which to return data messages.
     * @return bool `true` if a PDF file was generated successfully.
     */
    public function convert_string_to_passthru(
        $xmlString,
        &$msgs = array(),
        &$dats = array()
    ) {
        $pathAndArgs = $this->getCommandLine();
        $pathAndArgs .= '--structured-log=buffered -';

        return $this->convert_internal_string_to_passthru(
            $pathAndArgs,
            $xmlString,
            $msgs,
            $dats
        );
    }

    /**
     * Convert an XML or HTML string to a PDF file.
     *
     * @param string $xmlString A string containing an XML or HTML document.
     * @param string $pdfPath The filename of the output PDF file.
     * @param array $msgs An optional array in which to return error and warning
     *                    messages.
     * @param array $dats An optional array in which to return data messages.
     * @return bool `true` if a PDF file was generated successfully.
     */
    public function convert_string_to_file(
        $xmlString,
        $pdfPath,
        &$msgs = array(),
        &$dats = array()
    ) {
        $pathAndArgs = $this->getCommandLine();
        $pathAndArgs .= '--structured-log=normal ';
        $pathAndArgs .= ' - -o "' . $pdfPath . '"';

        return $this->convert_internal_string_to_file(
            $pathAndArgs,
            $xmlString,
            $msgs,
            $dats
        );
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
     * Specify whether to warn about CSS.
     *
     * @param bool $noWarnCss `true` to disable warnings. Default value is `false`.
     * @return void
     */
    public function setNoWarnCss($noWarnCss)
    {
        $this->noWarnCss = $noWarnCss;
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
     * @param [type] $xinclude `true` to enable XInclude processing. Default
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
     * Specify HTTP authentication methods.
     *
     * @param string $authMethod Can take a value of: `"basic"`, `"digest"`,
     *                           `"ntlm"`, `"negotiate"`.
     * @return void
     */
    public function setAuthMethod($authMethod)
    {
        $valid = array('basic', 'digest', 'ntlm', 'negotiate');
        $lower = strtolower($authMethod);

        $this->authMethod = in_array($lower, $valid) ? $lower : '';
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
     *                     than 0.
     * @return void
     */
    public function setHttpTimeout($timeout)
    {
        $this->httpTimeout = $timeout;
    }

    /**
     * Specify a Set-Cookie header value.
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
     * Specify fallback ICC profile for uncalibrated CMYK.
     *
     * @param string $fallbackCmykProfile The fallback ICC profile.
     * @return void
     */
    public function setFallbackCmykProfile($fallbackCmykProfile)
    {
        $this->fallbackCmykProfile = $fallbackCmykProfile;
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
     * @return void
     */
    public function setEncryptInfo(
        $keyBits,
        $userPassword,
        $ownerPassword,
        $disallowPrint = false,
        $disallowModify = false,
        $disallowCopy = false,
        $disallowAnnotate = false
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
        $cmdline = '"' . $this->exePath . '" ' . $this->styleSheets .
            $this->scripts . $this->fileAttachments . $this->remaps;

        if ($this->inputType != 'auto') {
            $cmdline .=  '-i "' . $this->inputType . '" ';
        }

        if ($this->javascript) {
            $cmdline .= '--javascript ';
        }

        if ($this->baseURL != '') {
            $cmdline .= '--baseurl="' . $this->baseURL . '" ';
        }

        if ($this->doXInclude == false) {
            $cmdline .= '--no-xinclude ';
        } else {
            $cmdline .= '--xinclude ';
        }

        if ($this->xmlExternalEntities == true) {
            $cmdline .= '--xml-external-entities ';
        }

        if ($this->noLocalFiles == true) {
            $cmdline .= '--no-local-files ';
        }

        if ($this->noNetwork == true) {
            $cmdline .= '--no-network ';
        }

        if ($this->httpProxy != '') {
            $cmdline .= '--http-proxy="' . $this->httpProxy . '" ';
        }

        if ($this->httpTimeout != '') {
            $cmdline .= '--http-timeout="' . $this->httpTimeout . '" ';
        }

        if ($this->cookie != '') {
            $cmdline .= '--cookie="' . $this->cookie . '" ';
        }

        if ($this->cookieJar != '') {
            $cmdline .= '--cookiejar="' . $this->cookieJar . '" ';
        }

        if ($this->sslCaCert != '') {
            $cmdline .= '--ssl-cacert="' . $this->sslCaCert . '" ';
        }

        if ($this->sslCaPath != '') {
            $cmdline .= '--ssl-capath="' . $this->sslCaPath . '" ';
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

        if ($this->logFile != '') {
            $cmdline .= '--log="' . $this->logFile . '" ';
        }

        if ($this->verbose) {
            $cmdline .= '--verbose ';
        }

        if ($this->debug) {
            $cmdline .= '--debug ';
        }

        if ($this->noWarnCss) {
            $cmdline .= '--no-warn-css ';
        }

        if ($this->fileRoot != '') {
            $cmdline .= '--fileroot="' . $this->fileRoot . '" ';
        }

        if ($this->licenseFile != '') {
            $cmdline .= '--license-file="' . $this->licenseFile . '" ';
        }

        if ($this->licenseKey != '') {
            $cmdline .= '--license-key="' . $this->licenseKey . '" ';
        }

        if ($this->embedFonts == false) {
            $cmdline .= '--no-embed-fonts ';
        }

        if ($this->subsetFonts == false) {
            $cmdline .= '--no-subset-fonts ';
        }

        if ($this->noArtificialFonts == true) {
            $cmdline .= '--no-artificial-fonts ';
        }

        if ($this->authMethod != '') {
            $cmdline .=  '--auth-method="' .
                $this->cmdlineArgEscape($this->authMethod) . '" ';
        }

        if ($this->authUser != '') {
            $cmdline .= '--auth-user="' .
                $this->cmdlineArgEscape($this->authUser) . '" ';
        }

        if ($this->authPassword != '') {
            $cmdline .= '--auth-password="' .
                $this->cmdlineArgEscape($this->authPassword) . '" ';
        }

        if ($this->authServer != '') {
            $cmdline .= '--auth-server="' .
                $this->cmdlineArgEscape($this->authServer) . '" ';
        }

        if ($this->authScheme != '') {
            $cmdline .= '--auth-scheme="' .
                $this->cmdlineArgEscape($this->authScheme) . '" ';
        }

        if ($this->noAuthPreemptive) {
            $cmdline .= '--no-auth-preemptive ';
        }

        if ($this->media != '') {
            $cmdline .= '--media="' .
                $this->cmdlineArgEscape($this->media) . '" ';
        }

        if ($this->pageSize != '') {
            $cmdline .= '--page-size="' .
                $this->cmdlineArgEscape($this->pageSize) . '" ';
        }

        if ($this->pageMargin != '') {
            $cmdline .= '--page-margin="' .
                $this->cmdlineArgEscape($this->pageMargin) . '" ';
        }

        if ($this->noAuthorStyle == true) {
            $cmdline .= '--no-author-style ';
        }

        if ($this->noDefaultStyle == true) {
            $cmdline .= '--no-default-style ';
        }

        if ($this->forceIdentityEncoding == true) {
            $cmdline .= '--force-identity-encoding ';
        }

        if ($this->compress == false) {
            $cmdline .= '--no-compress ';
        }

        if ($this->pdfOutputIntent != '') {
            $cmdline .= '--pdf-output-intent="' .
                $this->cmdlineArgEscape($this->pdfOutputIntent) . '" ';

            if ($this->convertColors == true) {
                $cmdline .= '--convert-colors ';
            }
        }

        if ($this->fallbackCmykProfile != '') {
            $cmdline .= '--fallback-cmyk-profile="' .
                $this->cmdlineArgEscape($this->fallbackCmykProfile) . '" ';
        }

        if ($this->pdfProfile != '') {
            $cmdline .= '--pdf-profile="' .
                $this->cmdlineArgEscape($this->pdfProfile) . '" ';
        }

        if ($this->pdfTitle != '') {
            $cmdline .= '--pdf-title="' .
                $this->cmdlineArgEscape($this->pdfTitle) . '" ';
        }

        if ($this->pdfSubject != '') {
            $cmdline .= '--pdf-subject="' .
                $this->cmdlineArgEscape($this->pdfSubject) . '" ';
        }

        if ($this->pdfAuthor != '') {
            $cmdline .= '--pdf-author="' .
                $this->cmdlineArgEscape($this->pdfAuthor) . '" ';
        }

        if ($this->pdfKeywords != '') {
            $cmdline .= '--pdf-keywords="' .
                $this->cmdlineArgEscape($this->pdfKeywords) . '" ';
        }

        if ($this->pdfCreator != '') {
            $cmdline .= '--pdf-creator="' .
                $this->cmdlineArgEscape($this->pdfCreator) . '" ';
        }

        if ($this->encrypt) {
            $cmdline .= '--encrypt ' . $this->encryptInfo;
        }

        if ($this->options != '') {
            $cmdline .= $this->cmdlineArgEscape($this->options) . ' ';
        }

        return $cmdline;
    }

    private function convert_internal_file_to_file($pathAndArgs, &$msgs, &$dats)
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

    private function convert_internal_string_to_file(
        $pathAndArgs,
        $xmlString,
        &$msgs,
        &$dats
    ) {
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
            fwrite($pipes[0], $xmlString);
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

    private function convert_internal_file_to_passthru(
        $pathAndArgs,
        &$msgs,
        &$dats
    ) {
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

    private function convert_internal_string_to_passthru(
        $pathAndArgs,
        $xmlString,
        &$msgs,
        &$dats
    ) {
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
            fwrite($pipes[0], $xmlString);
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
