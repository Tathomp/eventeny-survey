const form = document.getElementById("question-container");
let currentQuestionNumber = 0;

let useID = false;
let currentId = 0;

const optionsCountMap = new Map();

// Fills the form with data from the model passed to the view
function populateForm()
{
    document.getElementById("primary").value = jsonData["primaryColor"];
    jsonData['question'].forEach(question => {
        createQuestionBlock(question);
    })
}


// Called when the "new question" button is pressed
// We build all the base HTML elements for the new question block here
function createQuestionBlock(questionArr)
{
    let questionItem = document.createElement("div");
    questionItem.className = 'survey-content-holder';

    if (questionArr["id"] != "")
    {
        useID = true;
        currentId = questionArr["id"];

        let questionIdContainer = generateHiddenField();
        questionIdContainer.setAttribute("name", getQuestionNamePrefix() +"[id][" +  questionArr["id"] +"]");
        questionItem.appendChild(questionIdContainer);
        questionItem.setAttribute('id', currentId);
    }
    else
    {
        currentQuestionNumber++;
        questionItem.setAttribute('id', currentQuestionNumber);
    }

    questionItem.appendChild(generateLabel("Question Type: "));

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

    // We want to mark our current question type as selected
    switch(questionArr['category_name'])
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

    // Delete question element and controls
    let deleteQuestion = document.createElement("button");
    deleteQuestion.innerHTML = "Delete Question";
    deleteQuestion.setAttribute('class', 'button');
    deleteQuestion.setAttribute('type', 'button');
    deleteQuestion.onclick = function () { questionDeletePopup(questionItem);};
    questionItem.appendChild(deleteQuestion);

    questionItem.appendChild(document.createElement("br"));

    // Required Toggle and labels
    let labelPrompt = generateLabel("Required Question?");
    questionItem.appendChild(labelPrompt);

    questionItem.appendChild(document.createElement("br"));

    let yesOption = generateLabel("Yes ")
    let noOption = generateLabel(" No")
    yesOption.setAttribute("class", "slider-label");
    noOption.setAttribute("class", "slider-label");

    questionItem.appendChild(noOption);

    let switchLabel = generateLabel("");

    let checkBox = document.createElement("input");
    checkBox.setAttribute("type", "checkbox");
    checkBox.setAttribute("name", getQuestionNamePrefix() + "[required]");
    checkBox.checked = questionArr['required'];

    let span = document.createElement("span");
    span.setAttribute("class", "slider round");
    switchLabel.setAttribute("class", "switch")

    switchLabel.appendChild(checkBox);
    switchLabel.appendChild(span);
    questionItem.appendChild(switchLabel);
    questionItem.appendChild(yesOption);

    questionItem.appendChild(document.createElement("br"));

    //label element for question type dropdown menu
    questionItem.appendChild(generateLabel("Question: "));

    let input = generateNewInputField();
    input.setAttribute('name', getQuestionNamePrefix() + "[prompt]");
    input.setAttribute("value", questionArr['question_prompt']);
    questionItem.appendChild(input);

    if("options" in questionArr)
    {
        let btn = document.createElement("button");

        btn.innerHTML = "Add option";
        btn.setAttribute("type", "button");
        btn.classList.add("button");

        btn.onclick = function() { generateNewOption(questionItem, {"choice":""}); };

        questionItem.appendChild(btn);

        optionsCountMap.set(questionItem, 0);

        questionArr['options'].forEach(option => {
            generateNewOption(questionItem, option)
            }
        )
    }

    form.appendChild(questionItem);
    return questionItem;
}

// Called by the 'new option' button
// Generate the html for a new option field and assigns it the appropriate name value
function generateNewOption(targetQuestionItem, optionArray)
{
    console.log("Options Array");
    console.log(optionArray);
    let questionItem = targetQuestionItem;

    optionsCountMap.set(questionItem, optionsCountMap.get(questionItem) + 1);

    if(optionArray.id == '' || optionArray.id == undefined)
    {
        console.log("no id")
        optionArray["id"]=optionsCountMap.get(questionItem);
    }
    else
    {
        console.log("id")
        console.log(optionArray.id)

        optionArray["id"] = optionArray.id;
    }

    let optionDiv = document.createElement("div");
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

//
// function initOptionQuestion(selectedOption)
// {
//     let questionItem = newQuestionBlock(selectedOption);
//     optionsCountMap.set(questionItem, 0);
//
//     //new otpion button
//     let btn = document.createElement("button");
//     btn.innerHTML = "Add option";
//     btn.setAttribute("type", "button");
//     btn.className = "button";
//     btn.onclick = function() { generateNewOption(questionItem); };
//     questionItem.appendChild(btn);
//
//     generateNewOption(questionItem);
//     return questionItem;
// }
//

// Confirms that the user wants to delete a question from the form
function questionDeletePopup(question)
{
    let response = confirm("Are you sure you want to delete Question?");
    if(response == true)
    {
        question.remove();
    }
}

// Used to clear out a question container when we switch the question type
function deleteCurrentQuestion(questionId)
{
    questionId.remove();
}


// Deletes the data for the previous question block and generates the base data for the new one
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

//////////
// Utilis
//////////
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

// WIth the proper formatting, PHP will arrange our POST data in a easy to parse way
//  https://www.php.net/manual/en/reserved.variables.post.php
function getQuestionNamePrefix()
{
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

// Helper functions that provide the default values for new questions and options
// The formatting of these arrays matches the formatting of the serialization of the survey/question/option models
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


