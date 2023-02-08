<?php

include __DIR__ . '/../src/Prince.php';

$one = __DIR__ . '/convert-1.html';
$two = array($one, $one);
$str = file_get_contents($one);

function p()
{
    // Edit path accordingly.
    $p = new Prince\Prince('/path/to/prince');
    $p->addStyleSheet(__DIR__ . '/convert-1.css');
    $p->setJavaScript(true);
    return $p;
}

/* TEST ARGS ******************************************************************/

$p = p();
$p->setVerbose(true);
$p->setDebug(true);
// Creates a log file in project root.
// $p->setLog('x');
$p->setNoWarnCssUnknown(true);
$p->setNoWarnCssUnsupported(true);

$p->setNoNetwork(true);
$p->setNoRedirects(true);
$p->setAuthUser('x');
$p->setAuthPassword('x');
$p->setAuthServer('x');
$p->setAuthScheme('https');
$p->addAuthMethod('basic');
$p->addAuthMethod('digest');
$p->setNoAuthPreemptive(true);
$p->setHttpProxy('x');
$p->setHttpTimeout(100);
$p->addCookie('x');
$p->addCookie('y');
$p->setCookieJar('x');
$p->setSslCaCert('x');
$p->setSslCaPath('x');
$p->setSslCert('x');
$p->setSslCertType('der');
$p->setSslKey('x');
$p->setSslKeyType('pem');
$p->setSslKeyPassword('x');
$p->setSslVersion('tlsv1');
$p->setInsecure(true);
$p->setNoParallelDownloads(true);

$p->setLicenseFile('x');
$p->setLicenseKey('x');

$p->setInputType('html');
$p->setBaseUrl('.');
$p->addRemap('x', 'y');
$p->addRemap('i', 'j');
$p->setXInclude(true);
$p->setXmlExternalEntities(true);
$p->setIframes(true);
$p->setNoLocalFiles(true);

$p->setJavaScript(true);
$p->addScript('x');
$p->addScript('y');
$p->setMaxPasses(5);

$p->addStyleSheet('x');
$p->addStyleSheet('y');
$p->setMedia('x');
$p->setPageSize('x');
$p->setPageMargin('x');
$p->setNoAuthorStyle(true);
$p->setNoDefaultStyle(true);

$p->setPdfId('x');
$p->setPdfScript('x');
$p->addPdfEventScript('will-print', 'x');
$p->clearPdfEventScripts();
$p->addPdfEventScript('will-close', 'y');
$p->addPdfEventScript('will-close', 'z');
$p->setPdfLang('x');
$p->setPdfProfile('pdf/a-3b');
$p->setPdfOutputIntent('x', true);
$p->addFileAttachment('x');
$p->addFileAttachment('y');
$p->setNoArtificialFonts(true);
$p->setEmbedFonts(false);
$p->setSubsetFonts(false);
// Fails conversion if enabled, due to being unable to find any fonts.
// $p->setSystemFonts(false);
$p->setForceIdentityEncoding(true);
$p->setCompress(false);
$p->setNoObjectStreams(true);
$p->setFallbackCmykProfile('x');
$p->setTaggedPdf(true);
$p->setPdfForms(true);
$p->setCssDpi(100);

$p->setPdfTitle('x');
$p->setPdfSubject('x');
$p->setPdfAuthor('x');
$p->setPdfKeywords('x');
$p->setPdfCreator('x');
$p->setPdfXmp('x');

$p->setEncrypt(true);
$p->setEncryptInfo(
    40,
    'x',
    'x',
    true,
    true,
    true,
    true,
    true,
    true
);

$p->setRasterFormat('png');
$p->setRasterJpegQuality(100);
$p->setRasterPage(100);
$p->setRasterDpi(100);
$p->setRasterThreads(100);
$p->setRasterBackground('white');

assert($p->convertFileToFile($one, __DIR__ . '/args.pdf'));

/* TEST FAILSAFE **************************************************************/

$p = p();
$p->setFailDroppedContent(true);
$p->setFailMissingResources(true);
$p->setFailStrippedTransparency(true);
$p->setFailMissingGlyphs(true);
$p->setFailPdfProfileError(true);
$p->setFailPdfTagError(true);
$p->setFailInvalidLicense(true);

assert($p->convertFileToFile($one, __DIR__ . '/failsafe-1.pdf'));

$p = p();
$p->setFailSafe(true);

assert($p->convertFileToFile($one, __DIR__ . '/failsafe-2.pdf'));

/* TEST CONVERT ***************************************************************/

assert(p()->convertFile($one));

assert(p()->convertFileToFile($one, __DIR__ . '/convert-2.pdf'));

assert(p()->convertMultipleFiles($two, __DIR__ . '/convert-3.pdf'));

ob_start();
assert(p()->convertFileToPassthru($one));
$contents = ob_get_clean();
file_put_contents(__DIR__ . '/convert-4.pdf', $contents);

ob_start();
assert(p()->convertMultipleFilesToPassthru($two));
$contents = ob_get_clean();
file_put_contents(__DIR__ . '/convert-5.pdf', $contents);

ob_start();
assert(p()->convertStringToPassthru($str));
$contents = ob_get_clean();
file_put_contents(__DIR__ . '/convert-6.pdf', $contents);

assert(p()->convertStringToFile($str, __DIR__ . '/convert-7.pdf'));

$inputList = tmpfile();
$inputListPath = stream_get_meta_data($inputList)['uri'];
fwrite($inputList, $one . "\n" . $one);
assert(p()->convertInputList($inputListPath,  __DIR__ . '/convert-8.pdf'));
fclose($inputList);

$inputList = tmpfile();
$inputListPath = stream_get_meta_data($inputList)['uri'];
fwrite($inputList, $one . "\n" . $one . "\n" . $one);
ob_start();
assert(p()->convertInputListToPassthru($inputListPath));
$contents = ob_get_clean();
file_put_contents(__DIR__ . '/convert-9.pdf', $contents);
fclose($inputList);

/* TEST RASTERIZE *************************************************************/

assert(p()->rasterizeFile($one, __DIR__ . '/rasterize-1-%02d.png'));

assert(p()->rasterizeMultipleFiles($two, __DIR__ . '/rasterize-2-%02d.png'));

$p = p();
$p->setRasterPage(1);
$p->setRasterFormat('png');
ob_start();
assert($p->rasterizeFileToPassthru($one));
$contents = ob_get_clean();
file_put_contents(__DIR__ . '/rasterize-3.png', $contents);

$p = p();
$p->setRasterPage(2);
$p->setRasterFormat('png');
ob_start();
assert($p->rasterizeMultipleFilesToPassthru($two));
$contents = ob_get_clean();
file_put_contents(__DIR__ . '/rasterize-4.png', $contents);

$p = p();
$p->setRasterPage(1);
$p->setRasterFormat('png');
ob_start();
assert($p->rasterizeStringToPassthru($str));
$contents = ob_get_clean();
file_put_contents(__DIR__ . '/rasterize-5.png', $contents);

assert(p()->rasterizeStringToFile($str, __DIR__ . '/rasterize-6-%02d.png'));

$inputList = tmpfile();
$inputListPath = stream_get_meta_data($inputList)['uri'];
fwrite($inputList, $one . "\n" . $one);
assert(p()->rasterizeInputList($inputListPath, __DIR__ . '/rasterize-7-%02d.png'));
fclose($inputList);

$inputList = tmpfile();
$inputListPath = stream_get_meta_data($inputList)['uri'];
fwrite($inputList, $one . "\n" . $one . "\n" . $one);
$p = p();
$p->setRasterPage(3);
$p->setRasterFormat('png');
ob_start();
assert($p->rasterizeInputListToPassthru($inputListPath));
$contents = ob_get_clean();
file_put_contents(__DIR__ . '/rasterize-8.png', $contents);
fclose($inputList);
