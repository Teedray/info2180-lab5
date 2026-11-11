const countryInput = document.getElementById("country");
const lookupButton = document.getElementById("lookup");
const resultDiv = document.getElementById("result");

lookupButton.addEventListener("click", async function (event) {
    event.preventDefault();

    const params = new URLSearchParams({
        country: countryInput.value.trim()
    });

    lookupButton.disabled = true;
    resultDiv.textContent = "Loading...";

    try {
        const response = await fetch("world.php?" + params.toString());

        if (!response.ok) {
            throw new Error("Request failed");
        }

        resultDiv.innerHTML = await response.text();
    } catch (error) {
        console.error(error);
        resultDiv.textContent = "Unable to retrieve countries. Please try again.";
    } finally {
        lookupButton.disabled = false;
    }
});