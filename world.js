const countryInput = document.getElementById("country");
const lookupButton = document.getElementById("lookup");
const citiesButton = document.getElementById("lookup-cities");
const resultDiv = document.getElementById("result");

async function lookup(type) {
    const params = new URLSearchParams({
        country: countryInput.value.trim(),
        lookup: type
    });

    lookupButton.disabled = true;
    citiesButton.disabled = true;
    resultDiv.textContent = "Loading...";

    try {
        const response = await fetch("world.php?" + params.toString());

        if (!response.ok) {
            throw new Error("Request failed");
        }

        resultDiv.innerHTML = await response.text();
    } catch (error) {
        console.error(error);
        resultDiv.textContent = "Unable to retrieve results. Please try again.";
    } finally {
        lookupButton.disabled = false;
        citiesButton.disabled = false;
    }
}

lookupButton.addEventListener("click", function () {
    lookup("countries");
});

citiesButton.addEventListener("click", function () {
    lookup("cities");
});