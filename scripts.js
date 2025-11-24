function trackMood() {
    const moodSelect = document.getElementById('mood');
    const moodResult = document.getElementById('mood-result');
    const selectedMood = moodSelect.value;
    
    // Store mood in localStorage
    const today = new Date().toISOString().split('T')[0];
    localStorage.setItem(today, selectedMood);
    
    // Display result
    moodResult.textContent = `You're feeling ${selectedMood} today. Take care!`;
    
    // Provide a suggestion based on mood
    const suggestion = getMoodSuggestion(selectedMood);
    moodResult.textContent += ` ${suggestion}`;
}

function getMoodSuggestion(mood) {
    const suggestions = {
        happy: "Great! Why not share your positivity with someone today?",
        sad: "Remember, it's okay to not be okay. Consider talking to a friend or professional.",
        stressed: "Try some deep breathing exercises or take a short walk to clear your mind.",
        anxious: "Grounding techniques can help. Try naming 5 things you can see, 4 you can touch, 3 you can hear, 2 you can smell, and 1 you can taste.",
        neutral: "This could be a good time for self-reflection. What would you like to accomplish today?"
    };
    return suggestions[mood] || "Take some time for self-care today.";
}

// Smooth scrolling for navigation
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});

// Initialize mood tracker on page load
window.onload = function() {
    const today = new Date().toISOString().split('T')[0];
    const savedMood = localStorage.getItem(today);
    if (savedMood) {
        document.getElementById('mood').value = savedMood;
        trackMood();
    }
};
