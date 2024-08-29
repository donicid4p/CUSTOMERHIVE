async function main() {

    if (webIntegrationId && webIntegrationId !== '') {
        const check = await checkLoggedIn();

        if (check.status === 401) {
            const isLoggedIn = await jwtLogin();

        }
    }

    
    

    var selState;
    var query;
    var filters;

    var host_q = '';
    if (host.includes("https://") || host.includes("http://")) {
        host_q = host.split("//")[1];
    }

    
    var config = {
        host: host_q, 
        prefix: "/"+prefix+"/", 
        port: 443, 
        isSecure: true, 
    };

    if (webIntegrationId !== '') {
        config.webIntegrationId = webIntegrationId;
    }

    const baseUrl = (config.isSecure ? 'https://' : 'http://' ) + config.host + (config.port ? ':' + config.port : '') + config.prefix;

    var configuration = {
        baseUrl: baseUrl + 'resources',
    };

    if (webIntegrationId !== '') {
        configuration.webIntegrationId = webIntegrationId;
    }

    console.log('configuration');
    console.log(configuration);

    require.config(configuration);

    require(["js/qlik"], function (qlik) {
        if (!qlik) {
            console.error("Il modulo qlik non è stato caricato correttamente.");
            return;
        }
        
        qlik.setOnError(function (error) {
            var appdoc = document.getElementById(appId);
            var text_danger = appdoc.getElementsByClassName('text-danger');
            
            if (text_danger.length > 0) {
                text_danger[0].append(error.message);
            } else {
                console.error(error.message);
            }
        });

        var x = document.cookie;

        var app = qlik.openApp(appId, config);
        objectDisplay(app);

        var title = document.getElementById('title');
        title.innerHTML = "";

    });
}
function objectDisplay(app) {
    var title = document.getElementById('title');
        title.innerHTML = "Loading Object. Please wait...";
    if (objectid == 'CurrentSelections') {
        navbar(app);
    } else {
    app.visualization.get(objectid).then(function (vis) {
                    vis.show(objectid);
                });
    }
}
function navbar(app) {

    app.getObject($('#CurrentSelections'), 'CurrentSelections');
    app.getObject($(parent.document).find('#CurrentSelections'), 'CurrentSelections');
}

function objectsOptions(app) {
    app.getAppObjectList('masterobject', function (reply) {
        var str = "";

        $.each(reply.qAppObjectList.qItems, function (key, value) {
            var sheetId = value.qInfo.qId;
            var sheetTitle = value.qData.title;
            var name = value.qData.name;
            
            var sheetDiv = document.createElement('option');
            sheetDiv.className = 'masterobject-option';
            sheetDiv.value = sheetId;
            sheetDiv.innerHTML = name + ' (' + sheetId + ')';

            parent.document.getElementById('mashup_object').appendChild(sheetDiv);

            app.visualization.get(value.qInfo.qId).then(function (vis) {
                vis.show(value.qInfo.qId);
            });

            str += value.qData.title + ' ';
            $.each(value.qData.cells, function (k, v) {
                str += v.name + ' ';
            });
        });
    });
}

async function jwtLogin() {
    const authHeader = 'Bearer ' + qlik_token;
    console.log(authHeader);

    const response = await fetch(`${host}/login/jwt-session`, {
        credentials: 'include',
        mode: 'cors',
        method: 'POST',
        headers: {
            'Authorization': authHeader,
            'qlik-web-integration-id': webIntegrationId
        }
    });

    return response.ok;
}

async function checkLoggedIn() {
    const response = await fetch(`${host}/api/v1/users/me`, {
        mode: 'cors',
        credentials: 'include',
        headers: {
            'qlik-web-integration-id': webIntegrationId,
            'Authorization': 'Bearer ' + qlik_token
        }
    });

    return response;
}


main();
