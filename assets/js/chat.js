document.addEventListener("DOMContentLoaded", () => {
	const sendMsgBtn = document.querySelector(".send-btn");
	const chatInput = document.querySelector(".input-message");
	const chatBody = document.querySelector(".chat-body-inner");

	if (sendMsgBtn) {
		sendMsgBtn.addEventListener("click", (e) => {
			e.preventDefault();
			let newSelfChat = document.createElement("div");
			newSelfChat.classList.add("self-msg");
			newSelfChat.classList.add("single-message");
			
			newSelfChat.innerHTML = `<p>${chatInput.value}</p>`;

			chatBody.appendChild(newSelfChat);
			chatInput.value = '';

			sendMsgBtn.classList.add("disable");
			// process the ajax request and then enable the sendMsgBtn
			sendMsgBtn.classList.remove("disable");
		});
	}
});
