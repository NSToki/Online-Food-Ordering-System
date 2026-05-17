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
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        if (xhr.status === 200) {
            let response = JSON.parse(xhr.responseText);

            if (response.success) {
                document.getElementById("deliveryStatusText").innerHTML = response.status;
                alert(response.message);

                location.reload();
            } else {
                alert(response.message);
            }
        } else {
            alert("AJAX request failed");
        }
    };

    xhr.send("order_id=" + orderId + "&status=" + newStatus);
}