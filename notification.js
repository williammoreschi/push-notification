var isPushEnabled = false;
var pushButton = document.querySelector(".pushButton");
var desc = document.querySelector(".desc");
var desc_error = document.querySelector(".error");
var disableText = "Unsubscribe";
var enableText = "Subscribe";
var disableDesc = "Thank you message";
var enableDesc = "Click <span class='high'>Allow</span> button top left.";
document.addEventListener("DOMContentLoaded", function() {
	if (isPushEnabled) {
		unsubscribe();
	} else {
		subscribe();
	}
	serviceWorkerCall();
});

function serviceWorkerCall() {
	if ("serviceWorker" in navigator) {
		navigator.serviceWorker.register(window.location.href+"service-worker.js")
		.then(initialiseState);
	} else {
		var text = "Service workers aren't supported in this browser.";
		console.warn(text);
		var node = document.createElement("span");
		var textnode = document.createTextNode(text);
		node.appendChild(textnode);
		desc_error.appendChild(node);
	}
}

function initialiseState() {
	if (!("showNotification" in ServiceWorkerRegistration.prototype)) {
		var text = "Notifications aren't supported.";
		var node = document.createElement("p");
		var textnode = document.createTextNode(text);
		node.appendChild(textnode);
		desc_error.appendChild(node);
		console.log(text);
		return;
	}

	if (Notification.permission === "denied") {
		var text = "The user has blocked notifications.";
		var node = document.createElement("p");
		var textnode = document.createTextNode(text);
		node.appendChild(textnode);
		desc_error.appendChild(node);
		console.log(text);
		return;
	}

	if (!("PushManager" in window)) {
		var text = "Push messaging isn't supported.";
		var node = document.createElement("p");
		var textnode = document.createTextNode(text);
		node.appendChild(textnode);
		desc_error.appendChild(node);
		console.log(text);
		return;
	}

	navigator.serviceWorker.ready.then(function(serviceWorkerRegistration) {
		serviceWorkerRegistration.pushManager.getSubscription().then(function(subscription) {
			pushButton.disabled = false;
			if (!subscription) {
				return;
			}
			if (subscription) {
				sendSubscriptionToServer(subscription);
			}

			pushButton.textContent = disableText;
			desc.textContent = disableDesc;
			isPushEnabled = true;
		}).catch(function(e) {
			var text = "Error during getSubscription()";
			var node = document.createElement("p");
			var textnode = document.createTextNode(text+e);
			node.appendChild(textnode);
			desc_error.appendChild(node);
			console.log(text, e);
		});
	});
}

function subscribe() {
	pushButton.disabled = true;
	navigator.serviceWorker.ready.then(function(serviceWorkerRegistration) {
		serviceWorkerRegistration.pushManager.subscribe({ userVisibleOnly: true }).then(function(subscription) {
			isPushEnabled = true;
			pushButton.textContent = disableText;
			desc.textContent = disableDesc;
			pushButton.disabled = false;
			if (subscription) {
				sendSubscriptionToServer(subscription);
			}
		}).catch(function(e) {
			if (Notification.permission === "denied") {
				var text = "Warn: Permission for Notification is denied";
				var node = document.createElement("span");
				var textnode = document.createTextNode(text);
				node.appendChild(textnode);
				desc_error.appendChild(node);
				console.warn(text);

				pushButton.disabled = true;
			} else {
				var text = "Error: Unable to subscribe to push";
				var node = document.createElement("p");
				var textnode = document.createTextNode(text+e);
				node.appendChild(textnode);
				desc_error.appendChild(node);
				console.error(text, e);

				pushButton.disabled = true;
				pushButton.textContent = "Enable Push Messages";
			}
		});
	});
}

function unsubscribe() {
	pushButton.disabled = true;
	navigator.serviceWorker.ready.then(function(serviceWorkerRegistration) {
		serviceWorkerRegistration.pushManager
		.getSubscription()
		.then(function(pushSubscription) {
			if (!pushSubscription) {
				isPushEnabled = false;
				pushButton.disabled = false;
				pushButton.textContent = enableText;
				desc.textContent = enableDesc;
				return;
			}

			var temp = pushSubscription.endpoint.split("/");
			var registration_id = temp[temp.length - 1];
			deleteSubscriptionToServer(registration_id);

			pushSubscription.unsubscribe().then(function(successful) {
				pushButton.disabled = false;
				pushButton.textContent = enableText;
				desc.textContent = enableDesc;
				isPushEnabled = false;
			})
			.catch(function(e) {

				var text = "Error: thrown while unsbscribing from push messaging.";
				var node = document.createElement("p");
				var textnode = document.createTextNode(text);
				node.appendChild(textnode);
				desc_error.appendChild(node);
				console.error(text);
			});
		});
	});
}


// send subscription id to server
function sendSubscriptionToServer(subscription) {
	var temp = subscription.endpoint.split("/");
	var registration_id = temp[temp.length - 1];
	fetch(
		window.location.href+"api.php?funcao=insertGCM&dado="+registration_id,
		{
			method: "get"
		}
		).then(function(response) {
			return response.json();
		});
	}

	function deleteSubscriptionToServer(rid) {
		fetch(window.location.href+"api.php/deleteGCM?funcao=insertGCM&dado="+rid,{
			method: "get"
		}).then(function(response) {
			return response.json();
		});
	}