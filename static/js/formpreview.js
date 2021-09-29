function generatePreview()
{
    console.log("BUtotn clicked");
    let previewContainer = document.getElementById("preview");

    const formContainer = document.getElementById("dynamicform");
    buildContainer(previewContainer);

    buildQuestions(previewContainer);

}

function buildContainer(previewContainer)
{
    let container = document.createElement("div");
    container.setAttribute("class", "content-holder");

    let header = document.createElement("div");
    header.setAttribute("class", "content-header");
    header.innerHTML = document.getElementById("survey-name-input").value;
    let colorCode = document.getElementById("primary").value;
    header.style.background = (colorCode);
    container.style.border = "3px solid " + colorCode;

        console.log(colorCode);

    container.appendChild(header);
    previewContainer.appendChild(container);
}

function buildQuestions(previewContainer)
{

}
