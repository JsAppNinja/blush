<html>
<body>
<script src="//static.opentok.com/v2/js/opentok.min.js" ></script>

<script type="text/javascript">
    var apiKey = '<?= $this->config->item('open_tok_api_key') ?>';
    var sessionId = '<?=$session_id?>';
    var token = '<?=$token?>';

    function sessionConnectedHandler (event) {
        session.publish( publisher );
        subscribeToStreams(event.streams);
    }
    function subscribeToStreams(streams) {
        for (var i = 0; i < streams.length; i++) {
            var stream = streams[i];
            if (stream.connection.connectionId
                != session.connection.connectionId) {
                session.subscribe(stream, "opentok-subscriber", {
                    width:800,
                    height:600
                });
            }
        }
    }
    function streamCreatedHandler(event) {
        subscribeToStreams(event.streams);
    }

    var publisher = TB.initPublisher(apiKey, "opentok-publisher", {
        width:200,
        height:150,
        name: '<?=get_user_name()?>'
    });
    var session   = TB.initSession(sessionId);

    session.connect(apiKey, token);
    session.addEventListener("sessionConnected",
        sessionConnectedHandler);

    session.addEventListener("streamCreated",
        streamCreatedHandler);
</script>
<style>
    html, body, div, span, applet, object, iframe,
    h1, h2, h3, h4, h5, h6, p, blockquote, pre,
    a, abbr, acronym, address, big, cite, code,
    del, dfn, em, img, ins, kbd, q, s, samp,
    small, strike, strong, sub, sup, tt, var,
    b, u, i, center,
    dl, dt, dd, ol, ul, li,
    fieldset, form, label, legend,
    table, caption, tbody, tfoot, thead, tr, th, td,
    article, aside, canvas, details, embed,
    figure, figcaption, footer, header, hgroup,
    menu, nav, output, ruby, section, summary,
    time, mark, audio, video {
        margin: 0;
        padding: 0;
        border: 0;
        font-size: 100%;
        font: inherit;
        vertical-align: baseline;
    }

    #opentok-publisher {
        position:absolute;
        z-index:999;
        bottom:0;
        left:0;
    }

    #subscriber_1 {

    }
    body {
        background: #000 url('/assets/images/session-loading.png') center center no-repeat;
        min-height: 590px;
    }
</style>
<div id="opentok-publisher"></div>
<div id="opentok-subscriber"></div>
</body>
</html>