const form = document.getElementById("question-container");
let currentQuestionNumber = 0;
let useID = false;
let currentId = 0;

let optionsCountMap = new Map();


function generateDropDown()
{
    let paramString = '{ "id" : "", ' +
                        '"options" : [{"choice": "", "id":""}], ' +
    '                   "question_prompt": "", ' +
        '               "category_name": "Drop Down",' +
                        '"required": ' + true +'  }';
    let paramObj = JSON.parse(paramString);
    createQuestionBlock(paramObj);
}

function generateRadio()
{
    let paramString = '{ "id" : "", ' +
                        '"options" : [{"choice": "", "id":""}],' +
                        '"question_prompt": "", ' +
        '               "category_name": "Radio",' +
                        '"required": ' + true +'  }';
    let paramObj = JSON.parse(paramString);
    createQuestionBlock(paramObj);
}

function generateCheckBox()
{
    let paramString = '{ "id" : "",' +
                        '"options" : [{"choice": "", "id":""}], ' +
                        '"question_prompt": "", ' +
                        '"category_name": "Checkbox",' +
                        '"required": ' + true +'  }';
    let paramObj = JSON.parse(paramString);
    createQuestionBlock(paramObj);
}
function generateStarRating()
{
    let paramString = '{ "id" : "",' +
                        '"question_prompt": "", ' +
                        '"category_name": "Star Rating",' +
                        '"required": ' + true + ' }';
    let paramObj = JSON.parse(paramString);
    createQuestionBlock(paramObj);
}
function generateParagraph()
{
    let paramString = '{ "id" : "", ' +
                        '"question_prompt": "", ' +
                        '"category_name": "Paragraph",' +
        '               "required": ' + true + ' }';
    let paramObj = JSON.parse(paramString);
    createQuestionBlock(paramObj);
}
function generateShortAnswer()
{
    let paramString = '{ "id" : "",' +
                        '"question_prompt": "", ' +
                        '"category_name": "Short Answer",' +
                            '"required": ' + true + ' }';
    let paramObj = JSON.parse(paramString);
    createQuestionBlock(paramObj);
}




function selectForm(control, questId)
{
    let q = questId;
    currentQuestionNumber++;
    deleteCurrentQuestion(q); // we need to delete the question we're editing

    switch(control.value){
        case 'Drop Down':
            generateDropDown();
            break;
        case 'Checkbox':
            generateCheckBox();
            break;
        case 'Radio':
            generateRadio();
            break;
        case 'Star Rating':
            generateStarRating();
            break;
        case 'Paragraph':
            generateParagraph();
            break;
        case 'Short Answer':
            generateShortAnswer();
            break;
    }
}

function createQuestionBlock(qustionArray)
{
    let questionItem = document.createElement("div");
    questionItem.className = 'survey-content-holder';

    if (qustionArray["id"] != "")
    {
        useID = true;
        currentId = qustionArray["id"];

        let questionIdContainer = generateHiddenField();
        questionIdContainer.setAttribute("name", getQuestionNamePrefix() +"[id][" +  qustionArray["id"] +"]");
        questionItem.appendChild(questionIdContainer);
        questionItem.setAttribute('id', currentId);

    }
    else
    {
        currentQuestionNumber++;
        questionItem.setAttribute('id', currentQuestionNumber);

    }




    questionItem.appendChild(generateLabel("Question Type: "));

   // questionItem.appendChild(document.createElement("br"));

    let selectionType = document.createElement("select");
    selectionType.setAttribute('name', getQuestionNamePrefix() + "[category]")
    selectionType.addEventListener('change', () => { selectForm(selectionType, questionItem) }, false);

    let option1 = document.createElement("option");
    option1.text = "Drop Down";
    option1.value = "Drop Down";
    selectionType.appendChild(option1);

    let option2 = document.createElement("option");
    option2.text = "Checkbox";
    option2.value = "Checkbox";
    selectionType.appendChild(option2);

    let option3 = document.createElement("option");
    option3.text = "Star Rating";
    option3.value = "Star Rating";
    selectionType.appendChild(option3);

    let option4 = document.createElement("option");
    option4.text = "Paragraph";
    option4.value = "Paragraph";
    selectionType.appendChild(option4);

    let option5 = document.createElement("option");
    option5.text = "Short Answer";
    option5.value = "Short Answer";
    selectionType.appendChild(option5);

    let option6 = document.createElement("option");
    option6.text = "Radio";
    option6.value = "Radio";
    selectionType.appendChild(option6);
    console.log(qustionArray['category_name']);
    switch(qustionArray['category_name'])
    {
        case "Drop Down":
            option1.setAttribute('selected', 'selected')
            break;
        case "Checkbox":
            option2.setAttribute('selected', 'selected')
            break;
        case "Star Rating":
            option3.setAttribute('selected', 'selected')
            break;
        case "Paragraph":
            option4.setAttribute('selected', 'selected')
            break;
        case "Short Answer":
            option5.setAttribute('selected', 'selected')
            break;
        case "Radio":
            option6.setAttribute('selected', 'selected')
            break;
    }

    questionItem.appendChild(selectionType);

    let deleteQuestion = document.createElement("button");
    deleteQuestion.innerHTML = "Delete Question";
    deleteQuestion.setAttribute('class', 'button');
    deleteQuestion.setAttribute('type', 'button');
    deleteQuestion.onclick = function () { questionDeletePopup(questionItem);};
    questionItem.appendChild(deleteQuestion);

    questionItem.appendChild(document.createElement("br"));

    // Required Toggle
    // We'll need to give it an apporiate name and parse for it
    let labelPrompt = generateLabel("Required Question?");
    questionItem.appendChild(labelPrompt);

    questionItem.appendChild(document.createElement("br"));

    let yesOption = generateLabel("Yes ")
    let noOption = generateLabel(" No")
    yesOption.setAttribute("class", "slider-label");
    noOption.setAttribute("class", "slider-label");

    questionItem.appendChild(noOption);

    let switchLabel = generateLabel("")
    let checkBox = document.createElement("input");
    checkBox.setAttribute("type", "checkbox");
    checkBox.setAttribute("name", getQuestionNamePrefix() + "[required]");
    console.log(qustionArray)
    checkBox.checked = qustionArray['required'];
    let span = document.createElement("span");
    span.setAttribute("class", "slider round");
    switchLabel.setAttribute("class", "switch")
    switchLabel.appendChild(checkBox);
    switchLabel.appendChild(span);
    questionItem.appendChild(switchLabel);

    questionItem.appendChild(yesOption);


    questionItem.appendChild(document.createElement("br"));

    //label element for drop-down menu
    questionItem.appendChild(generateLabel("Question: "));

    let input = generateNewInputField();
    input.setAttribute('name', getQuestionNamePrefix() + "[prompt]");
    input.setAttribute("value", qustionArray['question_prompt']);
    questionItem.appendChild(input);

    if("options" in qustionArray)
    {
        let btn = document.createElement("button");
        btn.innerHTML = "Add option";
        btn.setAttribute("type", "button");
        btn.className = "button";
        optionsCountMap.set(questionItem, 0);

        btn.onclick = function() { generateNewOption(questionItem, {"choice":""}); };
        questionItem.appendChild(btn);

        qustionArray['options'].forEach(option => {
            generateNewOption(questionItem, option)
            }
        )
    }

    form.appendChild(questionItem);
    return questionItem;
}

function generateNewOption(targetQuestionItem, optionArray)
{
    console.log("Options Array");
    console.log(optionArray);
    if(optionArray["id"] == "")
    {
        optionsCountMap.set(targetQuestionItem, optionsCountMap.get(targetQuestionItem)+1);
        optionArray["id"]=optionsCountMap.get(targetQuestionItem);
    }

    let questionItem = targetQuestionItem;

    let optionDiv = document.createElement("div");
    optionsCountMap.set(questionItem, optionsCountMap.get(questionItem) + 1);

    let optionsId = generateHiddenField();
    optionsId.setAttribute("name", getQuestionNamePrefix() +"[id][" + optionArray["id"] +"]");
    questionItem.appendChild(optionsId);

    optionDiv.appendChild(generateLabel("Option: "));

    let optionInput = generateNewInputField();
    optionInput.setAttribute("name", getQuestionNamePrefix() + "[option][" + optionArray["id"] +"]")
    optionInput.setAttribute("value", optionArray["choice"])
    optionDiv.appendChild(optionInput);

    let deleteBtn = document.createElement("button");
    deleteBtn.onclick = function() { optionDiv.remove(); };
    deleteBtn.innerHTML = "Delete Option";
    deleteBtn.setAttribute('class', 'button');
    deleteBtn.setAttribute('type', 'button');

    optionDiv.appendChild(deleteBtn);
    questionItem.appendChild(optionDiv);
}


function initOptionQuestion(selectedOption)
{
    let questionItem = newQuestionBlock(selectedOption);
    optionsCountMap.set(questionItem, 0);

    //new otpion button
    let btn = document.createElement("button");
    btn.innerHTML = "Add option";
    btn.setAttribute("type", "button");
    btn.className = "button";
    btn.onclick = function() { generateNewOption(questionItem); };
    questionItem.appendChild(btn);

    generateNewOption(questionItem);
    return questionItem;
}

///
// Utils
////

function getQuestionNamePrefix()
{
    // https://www.php.net/manual/en/reserved.variables.post.php
    if(useID)
    {
        return "question[" +currentId + "]";

    }
    else
    {
        return "question[" +currentQuestionNumber + "]";

    }
}

function generateLabel(labelValue)
{
    let label = document.createElement("label");
    label.innerHTML = labelValue;
    label.className = 'label';
    return label;
}

function questionDeletePopup(question)
{
    let response = confirm("Are you sure you want to delete Question?");
    if(response == true)
    {
        question.remove();
    }
}

// THis is used for the when we change teh drop down menu box
function deleteCurrentQuestion(questionId)
{
    questionId.remove();
}

function generateNewInputField()
{
    let input = document.createElement('input');
    input.type = "text";

    return input;
}

function generateHiddenField()
{
    let idContainer = document.createElement("textarea");
    idContainer.className = "hidden-textarea";
    idContainer.style.visibility = "hidden";

    return idContainer;
}

function populateForm()
{
    // we couldn't echo this in with php
    document.getElementById("primary").value = jsonData["primaryColor"];
    jsonData['question'].forEach(question => {
        createQuestionBlock(question);
    })
}