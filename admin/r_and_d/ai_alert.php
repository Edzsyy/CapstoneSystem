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

        <!-- Organization Details -->
        <div class="mb-3">
            <label for="organizationName" class="form-label">Organization Name:</label>
            <input type="text" class="form-control" id="organizationName" placeholder="Enter organization name">
        </div>

        <div class="mb-3">
            <label for="contactNumber" class="form-label">Contact Number:</label>
            <input type="text" class="form-control" id="contactNumber" placeholder="Enter contact number">
        </div>

        <div class="mb-3">
            <label for="organizationAddress" class="form-label">Organization Address:</label>
            <input type="text" class="form-control" id="organizationAddress" placeholder="Enter address">
        </div>

        <div class="mb-3">
            <label for="websiteLink" class="form-label">Website Link:</label>
            <input type="url" class="form-control" id="websiteLink" placeholder="Enter website URL">
        </div>

        <div class="mb-3">
            <label for="notifyDays" class="form-label">Notify Business Owners Before (Days):</label>
            <input type="number" class="form-control" id="notifyDays" placeholder="Enter number of days before expiration" min="1">
        </div>

        <div class="mb-3">
            <label for="aiMessage" class="form-label">Notification Message for Upcoming Permit Expiration:</label>
            <textarea class="form-control" id="notifyMessage" rows="5" placeholder="Generate AI Notification message"></textarea>
            <button class="btn btn-secondary mt-2" id="generateAiMessage">Generate AI Message</button>
        </div>

        <div class="mb-3">
            <label for="expiredMessage" class="form-label">Expiration Message for Failed Renewal:</label>
            <textarea class="form-control" id="expiredMessage" rows="5" placeholder="Enter message for expired permit (failed renewal)"></textarea>
            <button class="btn btn-secondary mt-2" id="generateExpiredMessage">Generate AI Expiration Message</button>
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
    document.addEventListener("DOMContentLoaded", function() {
        fetchAISettings();
    });

    function fetchAISettings() {
        document.getElementById("loadingSpinner").classList.remove("d-none"); // Show loading

        fetch("../r_and_d/get_ai_setting.php")
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById("organizationName").value = data.data.organization_name || "";
                    document.getElementById("contactNumber").value = data.data.contact_number || "";
                    document.getElementById("organizationAddress").value = data.data.organization_address || "";
                    document.getElementById("websiteLink").value = data.data.website_link || "";
                    document.getElementById("notifyDays").value = data.data.notify_days || "";
                    document.getElementById("notifyMessage").value = data.data.notify_message || "";
                    document.getElementById("expiredMessage").value = data.data.expired_message || "";
                } else {
                    console.warn("No AI settings found.");
                }
            })
            .catch(error => console.error("Error fetching AI settings:", error))
            .finally(() => {
                document.getElementById("loadingSpinner").classList.add("d-none"); // Hide loading
            });
    }
    //saving setting to DB
    document.getElementById("saveAiSettings").addEventListener("click", function() {
        const organizationName = document.getElementById("organizationName").value.trim();
        const contactNumber = document.getElementById("contactNumber").value.trim();
        const organizationAddress = document.getElementById("organizationAddress").value.trim();
        const websiteLink = document.getElementById("websiteLink").value.trim();
        const notifyDays = document.getElementById("notifyDays").value.trim();
        const notifyMessage = document.getElementById("notifyMessage").value.trim();
        const expiredMessage = document.getElementById("expiredMessage").value.trim();

        if (notifyDays === "" || notifyMessage === "" || expiredMessage === "") {
            alert("⚠ Please fill in all required fields before saving.");
            return;
        }

        const formData = {
            organization_name: organizationName,
            contact_number: contactNumber,
            organization_address: organizationAddress,
            website_link: websiteLink,
            notify_days: parseInt(notifyDays, 10),
            notify_message: notifyMessage, // AI-generated message for notification
            expired_message: expiredMessage // AI-generated message for expired permit
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


    document.getElementById("generateAiMessage").addEventListener("click", function() {
        const notifyDays = document.getElementById("notifyDays").value.trim();

        if (notifyDays === "" || isNaN(notifyDays)) {
            alert("⚠ Please enter a valid number of days before generating AI message.");
            return;
        }

        const organizationName = document.getElementById("organizationName").value.trim();
        const contactNumber = document.getElementById("contactNumber").value.trim();
        const organizationAddress = document.getElementById("organizationAddress").value.trim();
        const websiteLink = document.getElementById("websiteLink").value.trim();

        document.getElementById("loadingSpinner").classList.remove("d-none"); // Show loading

        fetch("../r_and_d/notify_ai_message.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    notify_days: parseInt(notifyDays, 10),
                    organization_name: organizationName,
                    contact_number: contactNumber,
                    organization_address: organizationAddress,
                    website_link: websiteLink
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById("notifyMessage").value = data.ai_message; // ✅ Insert AI message into textarea
                } else {
                    alert("❌ AI message generation failed.");
                }
            })
            .catch(error => console.error("Error generating AI message:", error))
            .finally(() => {
                document.getElementById("loadingSpinner").classList.add("d-none"); // Hide loading
            });
    });

    document.getElementById("generateExpiredMessage").addEventListener("click", function() {
        const notifyDays = document.getElementById("notifyDays").value.trim();

        if (notifyDays === "" || isNaN(notifyDays)) {
            alert("⚠ Please enter a valid number of days before generating expiration message.");
            return;
        }

        const organizationName = document.getElementById("organizationName").value.trim();
        const contactNumber = document.getElementById("contactNumber").value.trim();
        const organizationAddress = document.getElementById("organizationAddress").value.trim();
        const websiteLink = document.getElementById("websiteLink").value.trim();

        document.getElementById("loadingSpinner").classList.remove("d-none"); // Show loading

        fetch("../r_and_d/expire_ai_message.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    notify_days: parseInt(notifyDays, 10),
                    organization_name: organizationName,
                    contact_number: contactNumber,
                    organization_address: organizationAddress,
                    website_link: websiteLink
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById("expiredMessage").value = data.ai_message; // ✅ Insert AI expiration message
                } else {
                    alert("❌ AI expiration message generation failed.");
                }
            })
            .catch(error => console.error("Error generating expired message:", error))
            .finally(() => {
                document.getElementById("loadingSpinner").classList.add("d-none"); // Hide loading
            });
    });
</script>