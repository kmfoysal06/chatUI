document.addEventListener("DOMContentLoaded", () => {
	const sendMsgBtn = document.querySelector(".send-btn");
	const chatInput = document.querySelector(".input-message");
	const chatBody = document.querySelector(".chat-body-inner");

  // initially scroll to bottom
  updateScroll(chatBody);

	if (sendMsgBtn) {
		sendMsgBtn.addEventListener("click", (e) => sendMessage(e));
	}
async function sendMessage(e){ 
		e.preventDefault();
  const message = chatInput.value;
    addMessage(message, true);
		chatInput.value = '';

  sendMsgBtn.classList.add("disable");
		// process the ajax request and then enable the sendMsgBtn
    let chatResponse = await processMessage(message);
  if(chatResponse) {
    addMessage(chatResponse, false);
  }
		sendMsgBtn.classList.remove("disable");
  updateScroll(chatBody);
}
function updateScroll(element) {
  element.scrollTop = element.scrollHeight;
}

function addMessage(text = "message", selfMsg = false) {
	

	let newSelfChat = document.createElement("div");
  if(selfMsg) {
		newSelfChat.classList.add("single-message");
    newSelfChat.classList.add("self-msg");
		newSelfChat.innerHTML = `<p>${text}</p>`;
  }else{
    newSelfChat.classList.add("single-message-inner");
    newSelfChat.innerHTML = `
							<img src="https://avatars.githubusercontent.com/u/95936171" />
							<div class="single-message-meta">
								<h3>Corporate Guy</h3>
								<div class="single-message">
									<p>${text}</p>
								</div>
							</div>
    `
  }
		
		chatBody.appendChild(newSelfChat);
}
  /**
   * use / to focus on the chat input if its not focused
   */
  document.addEventListener("keydown", (e) => {
    if (e.key === '/') {
      chatInput.focus();
   }
  });
/**
 * use enter to submit the message and if the input is not empty and focused
 * then submit the message
 */
if(chatInput){
  document.addEventListener("keydown", (e) => {
    if (e.key === 'Enter' && chatInput.value.trim() !== '' && chatInput === document.activeElement) {
      sendMessage(e);
    }
  }
  );
}
/**
 * Process Request for Chat
 */
async function processMessage(message = "Default Message"){
  const formData = JSON.stringify({
    "message" : message 
  });
  const response = await fetch("/rest.php", {
    method: "POST",
    body: formData
  });
  
  const result = await response.json();

  if(result.status === "success"){
    return result.message;
  }

  return false;
}
});
