// REFERENCE: https://jonathanmh.com/taking-full-page-screenshots-headless-chrome/

const CDP = require('chrome-remote-interface');
const argv = require('minimist')(process.argv.slice(2));
const fs = require('fs');

console.log(argv);

const targetURL = argv.url?argv.url:'https://sinri.cc';
//const viewport =[argv.viewportWidth||1440,argv.viewportHeight||900]; //[1440,900];
const viewportWidth=argv.viewportWidth?argv.viewportWidth:1440;
const viewportHeight = argv.viewportHeight ? argv.viewportHeight : 900;

const screenshotDelay = argv.delay?argv.delay:2000; // 2000; // ms
const fullPage = argv.fullPage || false;
const fitWindow = argv.fitWindow || false;
const isMobile = argv.isMobile || false;

const targetOutputFile=argv.outputFile||'camera-js-page.png';

if(fullPage){
    console.log("will capture full page")
}

CDP(async function(client){
    const {DOM, Emulation, Network, Page, Runtime} = client;

    // Enable events on domains we are interested in.
    await Page.enable();
    await DOM.enable();
    await Network.enable();

    // change these for your tests or make them configurable via argv
    var device = {
        width: viewportWidth,
        height: viewportHeight,
        deviceScaleFactor: 0,
        mobile: isMobile,
        fitWindow: fitWindow
    };

    // set viewport and visible size
    await Emulation.setDeviceMetricsOverride(device);
    await Emulation.setVisibleSize({width: viewportWidth, height: viewportHeight});

    await Page.navigate({url: targetURL});

    Page.loadEventFired(async() => {
        // original
        // if (fullPage) {
        //     const {root: {nodeId: documentNodeId}} = await DOM.getDocument();
        //     const {nodeId: bodyNodeId} = await DOM.querySelector({
        //         selector: 'body',
        //         nodeId: documentNodeId,
        //     });
        //
        //     const {model: {height}} = await DOM.getBoxModel({nodeId: bodyNodeId});
        //     await Emulation.setVisibleSize({width: device.width, height: height});
        //     await Emulation.setDeviceMetricsOverride({
        //         width: device.width,
        //         height: height,
        //         screenWidth: device.width,
        //         screenHeight: height,
        //         deviceScaleFactor: 1,
        //         fitWindow: false,
        //         mobile: false
        //     });
        //     await Emulation.setPageScaleFactor({pageScaleFactor:1});
        // }
        if (fullPage) {
            const {root: {nodeId: documentNodeId}} = await DOM.getDocument();
            const {nodeId: bodyNodeId} = await DOM.querySelector({
                selector: 'body',
                nodeId: documentNodeId,
            });

            const {model: {height, width}} = await DOM.getBoxModel({nodeId: bodyNodeId});
            await Emulation.setVisibleSize({width: device.width, height: height});
            await Emulation.setDeviceMetricsOverride({
                width: width,//device.width,
                height: height,
                screenWidth: width,//device.width,
                screenHeight: height,
                deviceScaleFactor: 1,
                fitWindow: false,
                mobile: false
            });
            await Emulation.setPageScaleFactor({pageScaleFactor:1});
        }
    });

    setTimeout(async function() {
        const screenshot = await Page.captureScreenshot({format: "png", fromSurface: true});
        const buffer = new Buffer(screenshot.data, 'base64');
        fs.writeFile(targetOutputFile, buffer, 'base64', function(err) {
            if (err) {
                console.error(err);
                process.exit(1);
            } else {
                console.log('Screenshot saved to file: ' + targetOutputFile);
                process.exit(0);
            }
        });
        client.close();
    }, screenshotDelay);

}).on('error', err => {
    console.error('Cannot connect to browser:', err);
process.exit(1);
});