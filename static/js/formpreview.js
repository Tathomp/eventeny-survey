function generatePreview()
{
    console.log("BUtotn clicked");
    let previewContainer = document.getElementById("preview");

    while(previewContainer.firstChild){
        previewContainer.removeChild(previewContainer.firstChild);
    }

    const formContainer = document.getElementById("dynamicform");
    let container = buildContainer(previewContainer);



    let questions = document.getElementsByClassName("survey-content-holder");

    for(let i = 1; i < questions.length; i++)
    {
        console.log(questions[i]);
        let currQuestion = questions[i];
        let currId = questions = currQuestion.id;
        let questionPrompt = document.createElement("label");

        questionPrompt.innerHTML = document.getElementsByName("question[" + currId + "][prompt]")[0].value;

        container.appendChild(questionPrompt);
        container.appendChild(document.createElement('br'));

        buildQuestionType(questions[i], i);
    }



}

function buildContainer(previewContainer)
{
    let container = document.createElement("div");
    container.setAttribute("class", "content-holder");

    let bodyContainer = document.createElement("div");
    bodyContainer.setAttribute("class", "content-body");

    let header = document.createElement("div");
    header.setAttribute("class", "content-header");
    header.innerHTML = document.getElementById("survey-name-input").value;
    let colorCode = document.getElementById("primary").value;
    header.style.background = (colorCode);
    container.style.border = "3px solid " + colorCode;

    container.appendChild(header);
    container.appendChild(bodyContainer);
    previewContainer.appendChild(container);
    return bodyContainer;
}


function buildQuestionType(questionPrompt, id)
{
    let questionSelect = document.getElementsByName("question[" + id + "][category]")[0];
    let selectValue = questionSelect.options[questionSelect.selectedIndex].value;

}