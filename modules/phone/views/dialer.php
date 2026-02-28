<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/vanilla-semantic-ui@0.0.1/dist/vanilla-semantic.min.css">

<style>
    * {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 20px 0;
    }

    .dialer-container {
        position: relative;
        max-width: 500px;
        margin: 0 auto;
    }

    .dialer-card {
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        padding: 30px;
        margin: 20px auto;
    }

    .status-header {
        text-align: center;
        padding: 20px 0;
        margin-bottom: 20px;
    }

    .status-indicator {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 24px;
        border-radius: 50px;
        background: #f8f9fa;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .status-indicator .status-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
        animation: pulse 2s infinite;
    }

    .status-indicator.green .status-dot {
        background: #10b981;
        box-shadow: 0 0 10px rgba(16, 185, 129, 0.5);
    }

    .status-indicator.orange .status-dot {
        background: #f59e0b;
        box-shadow: 0 0 10px rgba(245, 158, 11, 0.5);
    }

    .status-indicator.red .status-dot {
        background: #ef4444;
        box-shadow: 0 0 10px rgba(239, 68, 68, 0.5);
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    #output-lbl {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        margin: 0;
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
        border-collapse: separate;
        border-spacing: 8px;
    }

    #dialer_table tr td {
        text-align: center;
        height: 70px;
        width: 33%;
    }

    #dialer_table #call-to {
        padding: 0;
        border: none;
    }

    #dialer_table #dialer_input_td {
        border-bottom: none;
        padding: 0;
    }

    #dialer_table #dialer_input_td input {
        width: 100%;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 1.8em;
        padding: 15px 20px;
        text-align: center;
        font-weight: 600;
        color: #1f2937;
        background: #f9fafb;
        transition: all 0.3s ease;
    }

    #dialer_table #dialer_input_td input:focus {
        outline: none;
        border-color: #667eea;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    /* Remove arrows from type number input */
    #dialer_table #dialer_input_td input::-webkit-outer-spin-button,
    #dialer_table #dialer_input_td input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    #dialer_table #dialer_input_td input[type=number] {
        -moz-appearance: textfield;
    }

    #dialer_table #dialer_input_td input::placeholder {
        color: #9ca3af;
        opacity: 1;
    }

    #dialer_table #dialer_input_td input:-ms-input-placeholder {
        color: #9ca3af;
    }

    #dialer_table #dialer_input_td input::-ms-input-placeholder {
        color: #9ca3af;
    }

    #dialer_table .dialer_num_tr td {
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        background: #ffffff;
        border-radius: 16px;
        border: 2px solid #e5e7eb;
        transition: all 0.2s ease;
        font-weight: 600;
        font-size: 1.4em;
        color: #1f2937;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    #dialer_table .dialer_num_tr td:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #ffffff;
        border-color: #667eea;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    #dialer_table .dialer_num_tr td:active {
        transform: translateY(0);
        box-shadow: 0 2px 4px rgba(102, 126, 234, 0.3);
    }

    #dialer_table .dialer_num_tr td:nth-child(1),
    #dialer_table .dialer_num_tr td:nth-child(3) {
        border: 2px solid #e5e7eb;
    }

    #dialer_table .dialer_del_td {
        background: #ffffff;
        border-radius: 16px;
        border: 2px solid #e5e7eb;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    #dialer_table .dialer_del_td:hover {
        background: #fee2e2;
        border-color: #ef4444;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    #dialer_table .dialer_del_td img {
        cursor: pointer;
        transition: transform 0.2s ease;
    }

    #dialer_table .dialer_del_td:hover img {
        transform: scale(1.1);
    }

    #answer-btn, #hangup-btn, #call-btn {
        width: 100%;
        border-radius: 12px;
        padding: 14px 20px;
        font-weight: 600;
        font-size: 16px;
        border: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    #answer-btn {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #ffffff;
    }

    #answer-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
    }

    #hangup-btn {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: #ffffff;
    }

    #hangup-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
    }

    #call-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #ffffff;
    }

    #call-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    #answer-btn:disabled, #hangup-btn:disabled, #call-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    /* Ensure dimmer doesn't block clicks when not active */
    #loader.ui.dimmer:not(.active) {
        display: none !important;
        pointer-events: none !important;
    }

    #loader.ui.dimmer {
        position: relative;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 24px;
    }

    .ui.center.aligned.stackable.grid {
        margin: 0;
    }

    .ui.center.aligned.stackable.grid .row {
        margin: 0;
    }

    .ui.center.aligned.stackable.grid .column {
        padding: 0 !important;
    }
</style>

<div class="dialer-container">
<div class="dialer-card">
<div id='loader' class="ui dimmer">
    <div class="ui loader"></div>
</div>
<div class="status-header">
    <div class="status-indicator orange" id="status-indicator">
        <span class="status-dot"></span>
        <span id="output-lbl" style="font-size: 16px; font-weight: 600; color: #1f2937;">Ready</span>
    </div>
</div>
<div class="ui center aligned stackable grid">
    <div class="row">
        <div class="column">
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
                        const statusIndicator = document.getElementById('status-indicator');
                        statusIndicator.className = 'status-indicator orange';
                        outputLabel.textContent = 'Login';
                    }, false);

                    client.on('calling', function () {
                        hangupBtn.removeAttribute('disabled');
                        callto.setAttribute('disabled', 'disabled');
                        callBtn.setAttribute('disabled', 'disabled');
                        const statusIndicator = document.getElementById('status-indicator');
                        statusIndicator.className = 'status-indicator green';
                        outputLabel.textContent = 'Calling ' + client.getCounterpartNum().replace(`${username}.`, "") + '...';
                    }, false);

                    client.on('incomingcall', function (params) {
                        hangupBtn.removeAttribute('disabled');
                        answerBtn.removeAttribute('disabled');
                        callBtn.setAttribute('disabled', 'disabled');
                        callto.setAttribute('disabled', 'disabled');
                        const statusIndicator = document.getElementById('status-indicator');
                        statusIndicator.className = 'status-indicator green';
                        outputLabel.textContent = 'Incoming call from ' + params.from.replace(`${username}.`, "");
                    }, false);

                    client.on('callaccepted', function () {
                        hangupBtn.removeAttribute('disabled');
                        callBtn.setAttribute('disabled', 'disabled');
                        callto.setAttribute('disabled', 'disabled');
                        answerBtn.setAttribute('disabled', 'disabled');
                        dtmfKeyboard.style.display = 'initial';
                        const statusIndicator = document.getElementById('status-indicator');
                        statusIndicator.className = 'status-indicator green';
                        outputLabel.textContent = 'In conversation with ' + client.getCounterpartNum().replace(`${username}.`, "");
                    }, false);


                    client.on('hangup', function () {
                        hangupBtn.setAttribute('disabled', 'disabled');
                        answerBtn.setAttribute('disabled', 'disabled');
                        callBtn.removeAttribute('disabled');
                        callto.removeAttribute('disabled');
                        dtmfKeyboard.style.display = 'initial';
                        const statusIndicator = document.getElementById('status-indicator');
                        statusIndicator.className = 'status-indicator orange';
                        outputLabel.textContent = 'Call ended';
                    }, false);


                    //////////////////////add this
                    client.on('offline', function () {
                        const statusIndicator = document.getElementById('status-indicator');
                        statusIndicator.className = 'status-indicator red';
                        outputLabel.textContent = 'Token expired, refresh page';
                        loader.classList = "ui dimmer";
                        loader.style.display = 'none';
                    }, false);

                    client.on('missedcall', function () {
                        const statusIndicator = document.getElementById('status-indicator');
                        statusIndicator.className = 'status-indicator red';
                        outputLabel.textContent = 'Missed call from ' + client.getCounterpartNum().replace(`${username}.`, "");
                        loader.classList = "ui dimmer";
                        loader.style.display = 'none';
                    }, false);

                    client.on('closed', function () {
                        const statusIndicator = document.getElementById('status-indicator');
                        statusIndicator.className = 'status-indicator red';
                        outputLabel.textContent = 'connection closed, refresh page';
                        loader.classList = "ui dimmer";
                        loader.style.display = 'none';
                    }, false);
                })
                .catch(error => {
                    loader.classList = "ui dimmer";
                    loader.style.display = 'none';
                    const statusIndicator = document.getElementById('status-indicator');
                    statusIndicator.className = 'status-indicator red';
                    console.error('Africastalking error:', error);
                    if (error && typeof error === 'object' && error.message) {
                        outputLabel.textContent = 'Error: ' + error.message;
                    } else if (typeof error === 'string') {
                        outputLabel.textContent = 'Error: ' + error;
                    } else {
                        outputLabel.textContent = 'Connection error occurred';
                    }
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