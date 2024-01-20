const SECONDS = 60;
const NUMBERWORDS = 3000;
const DISPLAYEDAMMOUNT = 20;
const keySounds = [];
const deleteKeySound = new Audio("../audio/BACKSPACE.mp3");
let   keySoundIndex = 0;

let finished = 0;
let timeoutId;
let inputedText = "";

let started = 0;

for (let i = 0; i < 5; i++) {
    keySounds[i] = new Audio(`../audio/key${i + 1}.mp3`);
}

function initSpans() {
    const spans = document.getElementsByClassName("span");
    for (let i = 0; i < DISPLAYEDAMMOUNT; i++) {
        spans[i].classList.add("visible");
    }
}

//
function displaySpans() {
    const spans = document.getElementsByClassName("span");
    const completed = document.getElementsByClassName("completed");
    if (completed.length === NUMBERWORDS) {
        return ;
    }
    for (let i = 0; i < completed.length; i++) {
        if (spans[i].classList.contains("visible")) {
            spans[i].classList.remove("visible");
        }
    }
    for (let i = completed.length - 1; i < completed.length + DISPLAYEDAMMOUNT && i < NUMBERWORDS; i++) {
        spans[i].classList.add("visible");
    }
}

// Add the i tag to each letter in each span (for styling purposes)
function appendSpanContent(span, value) {
    value.split("").map((letter) => {
        const i = document.createElement("i");
        i.textContent = letter;
        i.classList.add("letters");
        span.appendChild(i);
    });
}

// Split the input entered and loop through each span checking if their value corresponds
function checkFilledWords(inputedText) {
    const words = inputedText.split(" ");
    const span = document.getElementsByClassName("span");
    for (let i = 0; i < span.length; i++) {
        if (i < words.length) {
            if (words[i] === span[i].textContent.trim()) {
                span[i].classList.add("completed");
                if (span[i + 1] !== undefined) {
                    span[i + 1].classList.add("highlighted");
                }
            }
        }
    }
}

// Loop through the i tags and check if their values corresponds to the value of input[index] (also for styling purposes)
function checkLetters(value) {
    const   letterTags = document.getElementsByClassName("letters");
    for (let i = 0; i < value.length && i < letterTags.length; i++) {
        if (letterTags[i].textContent == value[i]) {
            if (!letterTags[i].classList.contains("wrong"))
                letterTags[i].classList.add("correct");
            letterTags[i].classList.replace("wrong", "correct");
        }
        else {
            if (!letterTags[i].classList.contains("correct"))
                letterTags[i].classList.add("wrong");
            letterTags[i].classList.replace("correct", "wrong");
        }
    }
}


// Call the random quote api
// const fillWithQuotes = async () => {
//     try {
//         const response = await fetch("https://api.quotable.io/quotes/random");
//         if (!response.ok) {
//             throw new Error("HTTP error");
//         }
//         const quote = await response.json();
//         const parag = document.getElementById("parag");
//         let   length = quote[0].content.split(" ").length;
//         quote[0].content.split(" ").map((value, key) => {
//             let span = document.createElement("span");
//             if (key === 0) {
//                 span.classList.add("highlighted");
//             }
//             if (key !== length -1)
//                 appendSpanContent(span, value + " ");
//         else
//                 appendSpanContent(span, value);
//             parag.appendChild(span);
//         })
//     } catch (error) {
//         const svg = document.getElementById("svg");
//         svg.style.display = "block";
//         console.error(error.message);
//     }
// };

// Call the random words api, you can specify how many random words you want
const fillWithRandom = async (wordLength) => {
    try {
        const response = await fetch(`https://random-word-api.herokuapp.com/word?number=${NUMBERWORDS}`);
        if (!response.ok) {
            console.log("error");
        }
        const words = await response.json();
        const parag = document.getElementById("parag");
        words.map((value, key) => {
            if (value.length <= wordLength) {
                let span = document.createElement("span");
                span.classList.add("span");
                if (key === 0) {
                    span.classList.add("highlighted");
                }
                if (key != words.length - 1)
                    appendSpanContent(span, value + " ");
                else
                    appendSpanContent(span, value);
                parag.appendChild(span);
            }
        })
        initSpans();
    } catch (error) {
        const errorSvg = document.getElementById("errorSvg");
        const keyboardSvg = document.getElementById("keyboardSvg");
        const timeHeader = document.getElementById("timeHeader");
        errorSvg.style.display = "block";
        keyboardSvg.style.display = "none";
        timeHeader.style.display = "none";
    }
};

function handleKeys(e) {
    const regex = /^[a-zA-Z ]/;
    const p = document.getElementById("parag");
    if (!p.hasChildNodes()) {
        console.log("error\n");
        return ;
    }
    if (e.key == "Backspace" || e.key == "Delete") {
        const   letterTags = document.getElementsByClassName("letters");
        if (inputedText.length > 0 && inputedText.length <= letterTags.length) {
            letterTags[inputedText.length - 1].classList.remove("correct", "wrong");
        }
        inputedText = inputedText.substring(0, inputedText.length - 1);
        deleteKeySound.play();
        animateKeyboard("delete");
    } else if (e.key.length === 1 && regex.test(e.key)) {
        initTimer();
        inputedText += e.key;
        if (keySoundIndex > 4) {
            keySoundIndex = 0;
        }
        keySounds[keySoundIndex].play();
        keySoundIndex++;
        if (e.key !== " ") {
            animateKeyboard(e.key);
        } else {
            animateKeyboard("space");
        }
    }
}

function animateKeyboard (key) {
    const pressedKey = document.getElementById(`${key}Key`);
    const keyDetail = document.getElementById(`${key}Detail`);

    if (key == "delete") {
        pressedKey.children[0].style.fill = "#FF6666";
        keyDetail.children[0].style.fill = "red";
    } else {
        pressedKey.children[0].style.fill = "white";
        keyDetail.children[0].style.fill = "#666666";
    }
    setTimeout(() => {
        pressedKey.children[0].style.fill = "#111111";
        keyDetail.children[0].style.fill = "#111111";
    }, 200);
}

function timer() {
    const header = document.getElementById("timeHeader");
    const currentTime = new Date();
    const distanceInSeconds = Math.floor((currentTime - startTime) / 1000);
    if (distanceInSeconds >= 60) {
        finished = 1;
        const parag = document.getElementById("parag");
        parag.style.opacity = "0";
        header.textContent = `${document.getElementsByClassName("completed").length / 1} WPM`;
        clearTimeout(timeoutId);
    } else {
        const time = SECONDS - distanceInSeconds - 1;
        header.textContent = `00:${time >= 10 ? time : "0" + time}`;
        timeoutId = setTimeout(timer, 1000);
    }
}

function initTimer() {
    if (!started) {
        started++;
        startTime = new Date();
        timer();
    }
}

// 
document.addEventListener("keydown", (e) => {
    if (!finished) {
        handleKeys(e);
        checkFilledWords(inputedText);
        checkLetters(inputedText);
        if (inputedText === parag.textContent) {
            finished = 1;
            const parag = document.getElementById("parag");
            parag.style.opacity = "0";
            header.textContent = `${document.getElementsByClassName("completed").length / 1} WPM`;
            // finished all the words, hide the content and display a message with the wpm calculation.
        } else {
            const completedWords = document.getElementsByClassName("completed");
            if (completedWords.length > 0 && completedWords.length % DISPLAYEDAMMOUNT === 0) {
                displaySpans();
            }
        }
    }
});

fillWithRandom(5);