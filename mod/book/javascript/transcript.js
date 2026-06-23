document.addEventListener("DOMContentLoaded", function() {
    // Fetch the highlight color from the Moodle settings, defaulting to #008196 if not defined
    const highlightColor = window.MoodleSettings.transcriptHighlightColor || '#008196'; 
    console.log("Highlight color:", highlightColor);

    // Dynamically update the CSS to apply the highlight color
    const style = document.createElement('style');
    style.innerHTML = `.highlight {
        font-weight: bold;
        color: ${highlightColor};
    }`;
    document.head.appendChild(style);

    const iframes = document.querySelectorAll('iframe[src*="youtube.com/embed"]');

    iframes.forEach(function(iframe) {
        let src = iframe.src;

        // Check if enablejsapi is present in the src, if not, add it
        if (!src.includes("enablejsapi=1")) {
            src += src.includes('?') ? '&enablejsapi=1' : '?enablejsapi=1';
            iframe.src = src;
        }
    });
});

let player;
const splashArray = {};

document.addEventListener("DOMContentLoaded", () => {
    // Mapping time to caption lines
    document.querySelectorAll("div.caption-line").forEach(el => {
        const time = Math.floor(parseFloat(el.dataset.time));
        splashArray[time] = el;
    });
});

const tag = document.createElement('script');
tag.src = "https://www.youtube.com/iframe_api";
document.head.appendChild(tag);

const style = document.createElement('style');
style.innerHTML = `
    #transcript-scrollbox {
        margin-top: 10px;
        height: 400px;
        overflow-y: auto;
        padding: 10px;
        border: 1px solid #ccc;
        background-color: #f9f9f9;
        position: relative;
        scroll-behavior: smooth;
        box-sizing: border-box;
    }

    #transcript-scrollbox::-webkit-scrollbar {
        display: none;
    }

    .caption-line {
        display: flex;
        margin-bottom: 14px;
        overflow: hidden;
    }
    .caption-line-time {
        margin-right: 10px;
        font-weight: bold;
    }
`;
document.head.appendChild(style);

// Function to initialize YouTube player
function onYouTubeIframeAPIReady() {
    console.log("YouTube Iframe API is ready");

    const iframe = document.querySelector('iframe[src*="youtube.com/embed"]');
    if (!iframe) {
        console.error("YouTube iframe not found");
        return;
    }

    player = new YT.Player(iframe, {
        events: {
            'onStateChange': onPlayerStateChange
        }
    });
}

// Function to handle state change of the player
function onPlayerStateChange(event) {
    console.log('Player state:', event.data);

    if (event.data === YT.PlayerState.PLAYING) {
        console.log('Video is playing');
        updateTranscriptHighlight();
    }
}

// Function to update transcript highlight and scroll
function updateTranscriptHighlight() {
    const time = Math.floor(player.getCurrentTime());  // Get current time of the video

    if (splashArray[time]) {
        const line = splashArray[time];

        // If the line is not already highlighted
        if (line && !line.querySelector('.caption-line-text').classList.contains('highlight')) {
            // Add highlight to the current caption line
            line.querySelector('.caption-line-text').classList.add('highlight');

            const container = document.querySelector('#transcript-scrollbox');
            const scrollTo = line;

            // Calculate scroll position to match the line within the container
            const scrollPosition = scrollTo.offsetTop - container.offsetTop;

            // Only scroll the transcript section
            container.scrollTo({
                top: scrollPosition - 10,
                behavior: 'smooth'
            });

            // Remove highlight from other lines
            document.querySelectorAll('.caption-line-text.highlight').forEach(el => {
                if (el !== line.querySelector('.caption-line-text')) {
                    el.classList.remove('highlight');
                }
            });
        }
    }

    // Set timeout for the next update
    setTimeout(updateTranscriptHighlight, 800);
}
