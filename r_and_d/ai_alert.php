<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI-Based Expiration & Renewal Analytics</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h3>AI-Based Expiration & Renewal Analytics</h3>
    
    <div class="mb-3">
        <label for="notifyDays" class="form-label">Notify Business Owners Before (Days):</label>
        <input type="number" class="form-control" id="notifyDays" placeholder="Enter days">
    </div>

    <div class="mb-3">
        <label for="aiMessage" class="form-label">AI-Generated Notification Message:</label>
        <textarea class="form-control" id="aiMessage" rows="3" placeholder="Enter AI-generated message"></textarea>
        <button class="btn btn-secondary" id="generateAiMessage">Generate AI Message</button>

    </div>

    <button class="btn btn-primary" id="saveAiSettings">Save Settings</button>
</div>
<div id="loadingSpinner" class="text-center d-none">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    fetchAISettings();
});

function fetchAISettings() {
    document.getElementById("loadingSpinner").classList.remove("d-none"); // Show loading

    fetch("../r_and_d/get_ai_setting.php")
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById("notifyDays").value = data.data.notify_days;
                document.getElementById("aiMessage").value = data.data.ai_message;
            } else {
                console.warn("No AI settings found.");
            }
        })
        .catch(error => console.error("Error fetching AI settings:", error))
        .finally(() => {
            document.getElementById("loadingSpinner").classList.add("d-none"); // Hide loading
        });
}    

document.getElementById("saveAiSettings").addEventListener("click", function () {
    const notifyDays = document.getElementById("notifyDays").value.trim();
    const aiMessage = document.getElementById("aiMessage").value.trim();

    if (notifyDays === "" || aiMessage === "") {
        alert("⚠ Please fill in all fields before saving.");
        return;
    }

    const formData = {
        notify_days: parseInt(notifyDays, 10),
        ai_message: aiMessage
    };

    document.getElementById("loadingSpinner").classList.remove("d-none"); // Show loading

    fetch("../r_and_d/save_ai_setting.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("✅ AI settings saved successfully!");
        } else {
            alert("❌ Failed to save AI settings.");
        }
    })
    .catch(error => console.error("Error saving AI settings:", error))
    .finally(() => {
        document.getElementById("loadingSpinner").classList.add("d-none"); // Hide loading
    });
});

document.getElementById("generateAiMessage").addEventListener("click", function () {
    const notifyDays = document.getElementById("notifyDays").value.trim();

    if (notifyDays === "" || isNaN(notifyDays)) {
        alert("⚠ Please enter a valid number of days before generating AI message.");
        return;
    }

    document.getElementById("loadingSpinner").classList.remove("d-none"); // Show loading

    fetch("../r_and_d/generate_ai_message.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ notify_days: parseInt(notifyDays, 10) })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById("aiMessage").value = data.ai_message; // ✅ Insert AI message into textarea
        } else {
            alert("❌ AI message generation failed.");
        }
    })
    .catch(error => console.error("Error generating AI message:", error))
    .finally(() => {
        document.getElementById("loadingSpinner").classList.add("d-none"); // Hide loading
    });
});



</script>