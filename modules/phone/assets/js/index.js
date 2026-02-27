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

    fetch('/api/phone/capability_token', {
      headers: { "Content-Type": "application/json; charset=utf-8" , "authtoken": "xeyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyIjoiYXBpIHVzZXIiLCJuYW1lIjoiYXBpIHVzZXIiLCJBUElfVElNRSI6MTY4MTYyNDQyMX0.qFbOz9qnsdhn5ie5H7vLVP2utzvO9CbbhKdWPKZjb-4"},
      method: 'POST',
      mode:'same-origin',
      body: JSON.stringify({
        clientName: clientName.value
      })
    })
      .then(data => { return data.json() })
      .then(response => {
        let token = response.token;
        console.log(response)
        const at = new Africastalking.Client(token, {
          sounds: {
          dialing: '/sounds/dial.mp3',
          ringing: '/sounds/ring.mp3'
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
        }, false);

        client.on('missedcall', function () {
          outputLabel.textContent = 'Missed call from ' + client.getCounterpartNum().replace(`${username}.`, "");
          outputColor.classList = 'ui tiny red circular label';
          loader.classList = "ui dimmer";
        }, false);

        client.on('closed', function () {
          outputLabel.textContent = 'connection closed, refresh page';
          outputColor.classList = 'ui tiny red circular label';
          loader.classList = "ui dimmer";
        }, false);
      })
      .catch(error => {
        loader.classList = "ui dimmer";
        console.log(error)
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
      window.onload = ATlogin;


