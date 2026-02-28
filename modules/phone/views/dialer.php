<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/vanilla-semantic-ui@0.0.1/dist/vanilla-semantic.min.css">

<style>
    * {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    table.ui.table {
        display: none;
        font-size: 30px;
    }

    table.ui.table td {
        cursor: pointer;
    }

    table.ui.table td:hover {
        background-color: rgb(233, 233, 233);
    }

    #dialer_table {
        width: 100%;
        font-size: 1.5em;
    }

    #dialer_table tr td {
        text-align: center;
        height: 50px;
        width: 33%;
    }

    #dialer_table #dialer_input_td {
        border-bottom: 1px solid #fafafa;
    }

    #dialer_table #dialer_input_td input {
        width: 100%;
        border: none;
        font-size: 1.6em;
    }

    /* Remove arrows from type number input : Chrome, Safari, Edge, Opera */
    #dialer_table #dialer_input_td input::-webkit-outer-spin-button,
    #dialer_table #dialer_input_td input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Remove arrows from type number input : Firefox */
    #dialer_table #dialer_input_td input[type=number] {
        -moz-appearance: textfield;
    }

    #dialer_table #dialer_input_td input::placeholder {
        /* Chrome, Firefox, Opera, Safari 10.1+ */
        color: #cccccc;
        opacity: 1;
        /* Firefox */
    }

    #dialer_table #dialer_input_td input:-ms-input-placeholder {
        /* Internet Explorer 10-11 */
        color: #cccccc;
    }

    #dialer_table #dialer_input_td input::-ms-input-placeholder {
        /* Microsoft Edge */
        color: #cccccc;
    }

    #answer-btn {
        width: 100%;
    }

    #hangup-btn {
        width: 100%;
    }

    #call-btn {
        width: 100%;
    }

    #dialer_table #call-btxn {
        color: #ffffff;
        background-color: green;
        border: none;
        cursor: pointer;
        width: 100%;
        text-decoration: none;
        padding: 5px 32px;
        text-align: center;
        display: inline-block;
        margin: 10px 2px 4px 2px;
        transition: all 300ms ease;
        -moz-transition: all 300ms ease;
        --webkit-transition: all 300ms ease;
    }

    #dialer_table #call-btn:hover {
        background-color: #009d00;
    }

    #dialer_table .dialer_num_tr td {
        -webkit-touch-callout: none;
        /* iOS Safari */
        -webkit-user-select: none;
        /* Safari */
        -khtml-user-select: none;
        /* Konqueror HTML */
        -moz-user-select: none;
        /* Old versions of Firefox */
        -ms-user-select: none;
        /* Internet Explorer/Edge */
        user-select: none;
        /* Non-prefixed version, currently supported by Chrome, Edge, Opera and Firefox */
    }

    #dialer_table .dialer_num_tr td:nth-child(1) {
        border-right: 1px solid #fafafa;
    }

    #dialer_table .dialer_num_tr td:nth-child(3) {
        border-left: 1px solid #fafafa;
    }

    #dialer_table .dialer_num_tr:nth-child(1) td,
    #dialer_table .dialer_num_tr:nth-child(2) td,
    #dialer_table .dialer_num_tr:nth-child(3) td,
    #dialer_table .dialer_num_tr:nth-child(4) td {
        border-bottom: 1px solid #fafafa;
    }

    #dialer_table .dialer_num_tr .dialer_num {
        color: #0B559F;
        cursor: pointer;
    }

    #dialer_table .dialer_num_tr .dialer_num:hover {
        background-color: #fafafa;
    }

    #dialer_table .dialer_num_tr:nth-child(0) td {
        border-top: 1px solid #fafafa;
    }

    #dialer_table .dialer_del_td img {
        cursor: pointer;
    }

    /* Ensure dimmer doesn't block clicks when not active */
    #loader.ui.dimmer:not(.active) {
        display: none !important;
        pointer-events: none !important;
    }

    /* Ensure dimmer is scoped to its container, not the entire page */
    #loader.ui.dimmer {
        position: relative;
    }

    /* Make sure the dialer container allows clicks through when dimmer is hidden */
    .dialer-container {
        position: relative;
    }
</style>

<div class="dialer-container">
<div id='loader' class="ui dimmer">
    <div class="ui loader"></div>
</div>
<div class="ui center aligned stackable grid">
    <div class="row">
        <div class="column" style="padding: 3em 0 !important; ">

            <span id="output-color" class="ui tiny orange circular label"></span>&nbsp;<span
                class="ui large header" id="output-lbl">Ready</span>
            </br>
            <!-- <span id="env-lbl">ENV:  NODE_ENV </span> -->
        </div>
    </div>
    <div class="row hidden">
        <div class="six wide left aligned column">
            <div class="ui form">
                <div class="field">
                    <label>Agent Name</label>
                    <div class="ui acion input">
                        <input id="client-name" type="text" name="clientName" value="<?php echo $name; ?>"
                               placeholder="Agent Name: e.g Ejiro_maina" required>
                    </div>
                </div>
                <div class="field">
                    <button id="login-btn" class="ui positive button">Login</button>
                    <button id="logout-btn" class="ui negative button" disabled>Logout</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="six wide left aligned column">
            <div class="ui form" id="dtmf-keyboard">
                <table id="dialer_table">
                    <tr>
                        <td id="call-to" colspan="3"><input type="number"
                                                            placeholder="Enter phone number to call">
                        </td>
                    </tr>
                    <br>
                    <tr/>
                    <tr class="dialer_num_tr">
                        <td class="dialer_num" onclick="dialerClick('dial', 1)">1</td>
                        <td class="dialer_num" onclick="dialerClick('dial', 2)">2</td>
                        <td class="dialer_num" onclick="dialerClick('dial', 3)">3</td>
                    </tr>
                    <tr class="dialer_num_tr">
                        <td class="dialer_num" onclick="dialerClick('dial', 4)">4</td>
                        <td class="dialer_num" onclick="dialerClick('dial', 5)">5</td>
                        <td class="dialer_num" onclick="dialerClick('dial', 6)">6</td>
                    </tr>
                    <tr class="dialer_num_tr">
                        <td class="dialer_num" onclick="dialerClick('dial', 7)">7</td>
                        <td class="dialer_num" onclick="dialerClick('dial', 8)">8</td>
                        <td class="dialer_num" onclick="dialerClick('dial', 9)">9</td>
                    </tr>
                    <tr class="dialer_num_tr">
                        <td class="dialer_del_td">
                            <img alt="clear" onclick="dialerClick('clear', 'clear')"
                                 src="data:image/svg+xml;base64,PHN2ZyBhcmlhLWhpZGRlbj0idHJ1ZSIgZm9jdXNhYmxlPSJmYWxzZSIgZGF0YS1wcmVmaXg9ImZhcyIgZGF0YS1pY29uPSJlcmFzZXIiIHJvbGU9ImltZyIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB2aWV3Qm94PSIwIDAgNTEyIDUxMiIgY2xhc3M9InN2Zy1pbmxpbmUtLWZhIGZhLWVyYXNlciBmYS13LTE2IGZhLTd4Ij48cGF0aCBmaWxsPSIjYjFiMWIxIiBkPSJNNDk3Ljk0MSAyNzMuOTQxYzE4Ljc0NS0xOC43NDUgMTguNzQ1LTQ5LjEzNyAwLTY3Ljg4MmwtMTYwLTE2MGMtMTguNzQ1LTE4Ljc0NS00OS4xMzYtMTguNzQ2LTY3Ljg4MyAwbC0yNTYgMjU2Yy0xOC43NDUgMTguNzQ1LTE4Ljc0NSA0OS4xMzcgMCA2Ny44ODJsOTYgOTZBNDguMDA0IDQ4LjAwNCAwIDAgMCAxNDQgNDgwaDM1NmM2LjYyNyAwIDEyLTUuMzczIDEyLTEydi00MGMwLTYuNjI3LTUuMzczLTEyLTEyLTEySDM1NS44ODNsMTQyLjA1OC0xNDIuMDU5em0tMzAyLjYyNy02Mi42MjdsMTM3LjM3MyAxMzcuMzczTDI2NS4zNzMgNDE2SDE1MC42MjhsLTgwLTgwIDEyNC42ODYtMTI0LjY4NnoiIGNsYXNzPSIiPjwvcGF0aD48L3N2Zz4="
                                 width="22px" title="Clear"/>
                        </td>
                        <td class="dialer_num" onclick="dialerClick('dial', 0)">0</td>
                        <td class="dialer_del_td">
                            <img alt="delete" onclick="dialerClick('delete', 'delete')"
                                 src="data:image/svg+xml;base64,PHN2ZyBhcmlhLWhpZGRlbj0idHJ1ZSIgZm9jdXNhYmxlPSJmYWxzZSIgZGF0YS1wcmVmaXg9ImZhciIgZGF0YS1pY29uPSJiYWNrc3BhY2UiIHJvbGU9ImltZyIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB2aWV3Qm94PSIwIDAgNjQwIDUxMiIgY2xhc3M9InN2Zy1pbmxpbmUtLWZhIGZhLWJhY2tzcGFjZSBmYS13LTIwIGZhLTd4Ij48cGF0aCBmaWxsPSIjREMxQTU5IiBkPSJNNDY5LjY1IDE4MS42NWwtMTEuMzEtMTEuMzFjLTYuMjUtNi4yNS0xNi4zOC02LjI1LTIyLjYzIDBMMzg0IDIyMi4wNmwtNTEuNzItNTEuNzJjLTYuMjUtNi4yNS0xNi4zOC02LjI1LTIyLjYzIDBsLTExLjMxIDExLjMxYy02LjI1IDYuMjUtNi4yNSAxNi4zOCAwIDIyLjYzTDM1MC4wNiAyNTZsLTUxLjcyIDUxLjcyYy02LjI1IDYuMjUtNi4yNSAxNi4zOCAwIDIyLjYzbDExLjMxIDExLjMxYzYuMjUgNi4yNSAxNi4zOCA2LjI1IDIyLjYzIDBMMzg0IDI4OS45NGw1MS43MiA1MS43MmM2LjI1IDYuMjUgMTYuMzggNi4yNSAyMi42MyAwbDExLjMxLTExLjMxYzYuMjUtNi4yNSA2LjI1LTE2LjM4IDAtMjIuNjNMNDE3Ljk0IDI1Nmw1MS43Mi01MS43MmM2LjI0LTYuMjUgNi4yNC0xNi4zOC0uMDEtMjIuNjN6TTU3NiA2NEgyMDUuMjZDMTg4LjI4IDY0IDE3MiA3MC43NCAxNjAgODIuNzRMOS4zNyAyMzMuMzdjLTEyLjUgMTIuNS0xMi41IDMyLjc2IDAgNDUuMjVMMTYwIDQyOS4yNWMxMiAxMiAyOC4yOCAxOC43NSA0NS4yNSAxOC43NUg1NzZjMzUuMzUgMCA2NC0yOC42NSA2NC02NFYxMjhjMC0zNS4zNS0yOC42NS02NC02NC02NHptMTYgMzIwYzAgOC44Mi03LjE4IDE2LTE2IDE2SDIwNS4yNmMtNC4yNyAwLTguMjktMS42Ni0xMS4zMS00LjY5TDU0LjYzIDI1NmwxMzkuMzEtMTM5LjMxYzMuMDItMy4wMiA3LjA0LTQuNjkgMTEuMzEtNC42OUg1NzZjOC44MiAwIDE2IDcuMTggMTYgMTZ2MjU2eiIgY2xhc3M9IiI+PC9wYXRoPjwvc3ZnPg=="
                                 width="25px" title="Delete"/>
                        </td>
                    </tr>
                    <tr>

                        <td colspan="1">
                            <button id="answer-btn" class="ui positive button" disabled>Answer
                            </button>
                        </td>
                        <td colspan="1">
                            <button id="hangup-btn" class="ui negative button" disabled>Hangup
                            </button>
                        </td>
                        <td colspan="1">
                            <button id="call-btn" class="ui teal right labeled icon button"
                                    disabled>
                                <i class="phone icon"></i>
                                Call
                            </button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
<!-- End dialer-container -->

<!-- partial -->
<script src="https://unpkg.com/africastalking-client@1.0.2/build/africastalking.js"></script>
<!-- partial -->


<?php init_tail(); ?>
<!-- Load circleProgress plugin to prevent errors -->
<script src="<?php echo base_url('assets/plugins/jquery-circle-progress/circle-progress.min.js'); ?>"></script>
<script>
    $(function () {
        init_editor('.tinymce-email-description');
        init_editor('.tinymce-view-description');
        
        // Safety check and stub for circleProgress plugin to prevent errors
        if (typeof $.fn.circleProgress === 'undefined') {
            // Create a stub function to prevent errors if plugin fails to load
            $.fn.circleProgress = function(options) {
                console.warn('circleProgress plugin not available. Stub function called.');
                return this;
            };
        }
    });
</script>
<script>
    const username = 'Callcenter4CRM';


    const loginBtn = document.getElementById('login-btn'),
        outputLabel = document.getElementById('output-lbl'),
        envDiv = document.getElementById('env-lbl'),
        loader = document.getElementById('loader');
    loginBtn.addEventListener("click", function () {
        ATlogin();
    });
    console.log({ Africastalking })


    function ATlogin() {
        const clientName = document.getElementById('client-name');
        if (!(clientName.value.length === 0)) {
            loader.classList = "ui active dimmer";
            loader.style.display = '';

            fetch('/admin/phone/capability_token?clientName='+clientName.value.replace(/\s/g, "_"), {
                headers: { "Content-Type": "application/json; charset=utf-8"},
                method: 'GET',
                mode:'same-origin'
                // body: JSON.stringify({
                //     clientName: clientName.value
                // })
            })
                .then(data => { return data.json() })
                .then(response => {
                    let token = response.token;
                    console.log(response)
                    const at = new Africastalking.Client(token, {
                        sounds: {
                            dialing: 'https://phone.petanns.co.ke/sounds/dial.mp3',
                            ringing: 'https://phone.petanns.co.ke/sounds/ring.mp3'
                        }
                    })
                    return at;
                })
                .then(client => {
                    const logoutBtn = document.getElementById('logout-btn'),
                        hangupBtn = document.getElementById('hangup-btn'),
                        answerBtn = document.getElementById('answer-btn'),
                        callBtn = document.getElementById('call-btn'),
                        callto = document.getElementById('call-to'),



                        outputColor = document.getElementById('output-color'),
                        dtmfKeyboard = document.getElementById('dtmf-keyboard');

                    logoutBtn.addEventListener("click", function () {
                        client.hangup();
                        client.logout();
                    });

                    hangupBtn.addEventListener("click", function () {
                        client.hangup();
                    });

                    answerBtn.addEventListener("click", function () {
                        client.answer();
                    });

                    callBtn.addEventListener("click", function () {
//          let to = document.getElementById('call-to').value;
                        let to = $('#call-to input').val();
                        if (/^[a-zA-Z]+/.test(to)) { to = `${username}.${to}` }
                        client.call(to, false);
                    });

                    for (const key of dtmfKeyboard.querySelectorAll('td')) {
                        key.addEventListener("click", function (events) {
                            const text = events.target.innerHTML;
                            client.dtmf(text);
                        });
                    }

                    ////////////////////////webrtc events////////////////////////////

                    client.on('ready', function () {

//          envDiv.textContent = "ffff";
                        loginBtn.setAttribute('disabled', 'disabled');
                        clientName.setAttribute('disabled', 'disabled');
                        logoutBtn.removeAttribute('disabled');
                        callto.removeAttribute('disabled');
                        callBtn.removeAttribute('disabled');
                        callto.focus();
                        outputColor.classList = 'ui tiny green circular label';
                        outputLabel.textContent = 'Ready to make calls';
                        loader.classList = "ui dimmer";
                    loader.style.display = 'none';
                    }, false);


                    client.on('notready', function () {
                        loginBtn.removeAttribute('disabled');
                        clientName.removeAttribute('disabled');
                        logoutBtn.setAttribute('disabled', 'disabled');
                        callto.setAttribute('disabled', 'disabled');
                        callBtn.setAttribute('disabled', 'disabled');
                        outputLabel.textContent = 'Login';
                        outputColor.classList = 'ui tiny orange circular label'
                    }, false);

                    client.on('calling', function () {
                        hangupBtn.removeAttribute('disabled');
                        callto.setAttribute('disabled', 'disabled');
                        callBtn.setAttribute('disabled', 'disabled');
                        outputLabel.textContent = 'Calling ' + client.getCounterpartNum().replace(`${username}.`, "") + '...';
                        outputColor.classList = 'ui tiny green circular label'
                    }, false);

                    client.on('incomingcall', function (params) {
                        hangupBtn.removeAttribute('disabled');
                        answerBtn.removeAttribute('disabled');
                        callBtn.setAttribute('disabled', 'disabled');
                        callto.setAttribute('disabled', 'disabled');
                        outputLabel.textContent = 'Incoming call from ' + params.from.replace(`${username}.`, "");
                        outputColor.classList = 'ui tiny green circular label'
                    }, false);

                    client.on('callaccepted', function () {



                        hangupBtn.removeAttribute('disabled');
                        callBtn.setAttribute('disabled', 'disabled');
                        callto.setAttribute('disabled', 'disabled');
                        answerBtn.setAttribute('disabled', 'disabled');
                        dtmfKeyboard.style.display = 'initial';
                        outputLabel.textContent = 'In conversation with ' + client.getCounterpartNum().replace(`${username}.`, "");
                        outputColor.classList = 'ui tiny green circular label'
                    }, false);


                    client.on('hangup', function () {
                        hangupBtn.setAttribute('disabled', 'disabled');
                        answerBtn.setAttribute('disabled', 'disabled');
                        callBtn.removeAttribute('disabled');
                        callto.removeAttribute('disabled');
                        dtmfKeyboard.style.display = 'initial';
                        outputLabel.textContent = 'Call ended';
                        outputColor.classList = 'ui tiny orange circular label'
                    }, false);


                    //////////////////////add this
                    client.on('offline', function () {
                        outputLabel.textContent = 'Token expired, refresh page';
                        outputColor.classList = 'ui tiny red circular label';
                        loader.classList = "ui dimmer";
                        loader.style.display = 'none';
                    }, false);

                    client.on('missedcall', function () {
                        outputLabel.textContent = 'Missed call from ' + client.getCounterpartNum().replace(`${username}.`, "");
                        outputColor.classList = 'ui tiny red circular label';
                        loader.classList = "ui dimmer";
                        loader.style.display = 'none';
                    }, false);

                    client.on('closed', function () {
                        outputLabel.textContent = 'connection closed, refresh page';
                        outputColor.classList = 'ui tiny red circular label';
                        loader.classList = "ui dimmer";
                        loader.style.display = 'none';
                    }, false);
                })
                .catch(error => {
                    loader.classList = "ui dimmer";
                    loader.style.display = 'none';
                    console.error('Africastalking error:', error);
                    if (error && typeof error === 'object' && error.message) {
                        outputLabel.textContent = 'Error: ' + error.message;
                    } else if (typeof error === 'string') {
                        outputLabel.textContent = 'Error: ' + error;
                    } else {
                        outputLabel.textContent = 'Connection error occurred';
                    }
                    outputColor.classList = 'ui tiny red circular label';
                });
        }
        else {
            outputLabel.textContent = 'make sure client name is valid';
        }
    }

    function dialerClick(type, value) {
        let input = $('#call-to input');
        let input_val = $('#call-to input').val();
        if (type == 'dial') {
            input.val(input_val + value);
        } else if (type == 'delete') {
            input.val(input_val.substring(0, input_val.length - 1));
        } else if (type == 'clear') {
            input.val("");
        }
    }
    function myFunction() {
        // Code to run when page loads
        // console.log("Page loaded");
        ATlogin();
        // alert("Page loaded successfully"   );
    }
    // Initialize loader as hidden on page load
    window.addEventListener('DOMContentLoaded', function() {
        const loader = document.getElementById('loader');
        if (loader) {
            loader.classList.remove('active');
            loader.style.display = 'none';
        }
    });

    // Only auto-login if explicitly needed, otherwise let user click
    // window.onload = ATlogin; // Commented out to prevent auto-blocking



</script>

<!-- jQuery is already loaded by the main application, removing duplicate jQuery slim to avoid conflicts -->
<!-- Removed: jQuery slim, Popper, and Bootstrap as they conflict with main application's jQuery -->
</body>
</html>