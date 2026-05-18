function toggleOnlineStatus() {

    let xhr = new XMLHttpRequest();

    xhr.open("POST", "../../api/delivery_agent/toggle_online.php", true);

    xhr.onload = function () {

        if (xhr.status === 200) {

            let response = JSON.parse(xhr.responseText);

            if (response.success) {

                let statusText = document.getElementById("onlineStatusText");
                let toggleButton = document.getElementById("toggleOnlineBtn");

                if (response.is_online == 1) {

                    statusText.innerHTML = "Online";
                    statusText.className = "status online";

                    toggleButton.innerHTML = "Go Offline";

                } else {

                    statusText.innerHTML = "Offline";
                    statusText.className = "status offline";

                    toggleButton.innerHTML = "Go Online";
                }

                alert(response.message);

            } else {

                alert(response.message);
            }

        } else {

            alert("AJAX request failed");
        }
    };

    xhr.send();
}



function updateDeliveryStatus(newStatus) {

    let orderId = document.getElementById("orderId").value;

    let xhr = new XMLHttpRequest();

    xhr.open("POST", "../../api/delivery_agent/update_status.php", true);

    xhr.setRequestHeader(
        "Content-type",
        "application/x-www-form-urlencoded"
    );

    xhr.onload = function () {

        if (xhr.status === 200) {

            let response = JSON.parse(xhr.responseText);

            if (response.success) {

                document.getElementById("deliveryStatusText").innerHTML =
                    response.status;

                alert(response.message);

                location.reload();

            } else {

                alert(response.message);
            }

        } else {

            alert("AJAX request failed");
        }
    };

    xhr.send(
        "order_id=" + orderId +
        "&status=" + newStatus
    );
}



let previousOrderCount = null;

function checkNewAssignments() {
    let xhr = new XMLHttpRequest();

    xhr.open("GET", "../../api/delivery_agent/notifications.php", true);

    xhr.onload = function () {
        if (xhr.status === 200) {
            let response = JSON.parse(xhr.responseText);

            if (response.success) {
                let currentCount = parseInt(response.count);
                let notificationBox = document.getElementById("assignmentNotification");

                if (notificationBox) {
                    if (currentCount > 0) {
                        notificationBox.style.display = "inline-block";
                        notificationBox.innerHTML = "🔔 " + currentCount + " Available Assignment(s)";
                    } else {
                        notificationBox.style.display = "none";
                    }
                }
            }
        }
    };

    xhr.send();
}

function acceptOrder(orderId) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "../../api/delivery_agent/accept_order.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (xhr.status === 200) {
            let response = JSON.parse(xhr.responseText);
            if (response.success) {
                alert(response.message);
                window.location.href = "active_delivery.php";
            } else {
                alert(response.message);
                location.reload();
            }
        }
    };
    xhr.send("order_id=" + orderId);
}

function declineOrder(orderId) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "../../api/delivery_agent/decline_order.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (xhr.status === 200) {
            let response = JSON.parse(xhr.responseText);
            alert(response.message);
            location.reload();
        }
    };
    xhr.send("order_id=" + orderId);
}