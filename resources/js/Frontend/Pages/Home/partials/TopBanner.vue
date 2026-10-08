<template>
    <section class="hero">
        <video autoplay muted loop playsinline aria-hidden="true">
            <source
                src="/images/Bikes/Banner-images/5198907-uhd_4096_2160_25fps.mp4"
                type="video/mp4"
            />
        </video>

        <div class="card">
            <h1>What you want generate today?</h1>

            <!-- Input area -->
            <div class="input-box">
                <textarea
                    ref="inputEl"
                    v-model="prompt"
                    rows="2"
                    :placeholder="placeholder"
                    @keydown.enter.exact.prevent="submit"
                ></textarea>

                <div class="tools">
                    <div class="left">
                        <button type="button" class="icon-btn" aria-label="Attach a photo" @click="$emit('attach')">
                            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                <path d="M12 5v14M5 12h14" />
                            </svg>
                        </button>
                        <button type="button" class="icon-btn" aria-label="Browse by bike model" @click="$emit('models')">
                            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round">
                                <path d="M12 3 4 7.5v9L12 21l8-4.5v-9L12 3Z" />
                                <path d="M4 7.5 12 12l8-4.5M12 12v9" />
                            </svg>
                        </button>
                    </div>

                    <div class="right">
                        <button type="button" class="icon-btn" aria-label="Use voice input" @click="$emit('voice')">
                            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="9" y="3" width="6" height="12" rx="3" />
                                <path d="M5 11a7 7 0 0 0 14 0M12 18v3" />
                            </svg>
                        </button>
                        <button
                            type="button"
                            class="send"
                            aria-label="Send"
                            :disabled="!prompt.trim()"
                            @click="submit"
                        >
                            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 19V5M5 12l7-7 7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Ready-to-use prompts -->
            <p class="hint">Start with a ready-to-use prompt:</p>
            <div class="chips">
                <button
                    v-for="c in chips"
                    :key="c.label"
                    type="button"
                    class="chip"
                    @click="useChip(c)"
                >
                    <img :src="c.img" alt="" />
                    {{ c.label }}
                </button>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref } from "vue";

const emit = defineEmits(["submit", "attach", "models", "voice"]);

const placeholder = "create something wonderful.";
const prompt = ref("");
const inputEl = ref(null);

const chips = [
    {
        label: "Leave pattern",
        img: "",
        text: "Show me fuel system parts for my bike",
    },
    {
        label: "Rose follower pattern ",
        img: "",
        text: "I need an air or oil filter for my bike",
    },
    {
        label: "mix",
        img: "",
        text: "Which engine oil and fluids suit my bike?",
    },
];

function useChip(c) {
    prompt.value = c.text;
    inputEl.value?.focus();
}

function submit() {
    const text = prompt.value.trim();
    if (!text) return;
    emit("submit", text);
    prompt.value = "";
}
</script>

<style scoped>
.hero {
    position: relative;
    display: grid;
    place-items: center;
    min-height: 420px;
    padding: 40px 16px;
    background: #222;
    overflow: hidden;
}

video {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0.55;
}

/* Frosted glass card */
.card {
    position: relative;
    width: 100%;
    max-width: 700px;
    padding: 28px 18px 24px;
    text-align: center;
    color: white;
    background: rgba(70, 70, 85, 0.45);
    backdrop-filter: blur(18px) saturate(1.2);
    -webkit-backdrop-filter: blur(18px) saturate(1.2);
    border-radius: 10px;
}

h1 {
    margin: 0 0 22px;
    font-size: clamp(1.6rem, 4vw, 2.2rem);
    font-weight: 600;
    letter-spacing: 0.01em;
}

/* White input box */
.input-box {
    display: flex;
    flex-direction: column;
    gap: 6px;
    padding: 12px 14px;
    text-align: left;
    background: #fff;
    border-radius: 10px;
}

textarea {
    width: 100%;
    resize: none;
    padding: 4px 2px;
    color: #1f2430;
    font: inherit;
    font-size: 0.95rem;
    line-height: 1.4;
    background: transparent;
    border: 0;
    outline: none;
}

textarea::placeholder {
    color: #6b7280;
}

.tools {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.left,
.right {
    display: flex;
    align-items: center;
    gap: 6px;
}

.icon-btn {
    display: grid;
    place-items: center;
    width: 36px;
    height: 36px;
    color: #1f2430;
    background: transparent;
    border: 0;
    border-radius: 8px;
    cursor: pointer;
}

.icon-btn:hover {
    background: #f0f1f4;
}

.send {
    display: grid;
    place-items: center;
    width: 40px;
    height: 40px;
    color: #fff;
    background: #2f5bff;
    border: 0;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.15s, opacity 0.15s;
}

.send:hover:not(:disabled) {
    background: #1f47d9;
}

.send:disabled {
    opacity: 0.6;
    cursor: default;
}

.icon-btn:focus-visible,
.send:focus-visible,
.chip:focus-visible {
    outline: 2px solid #2f5bff;
    outline-offset: 2px;
}

/* Ready-to-use prompts */
.hint {
    margin: 22px 0 10px;
    font-size: 0.8rem;
    font-weight: 600;
    opacity: 0.9;
}

.chips {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 8px;
}

.chip {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 7px 14px;
    color: #1f2430;
    font: inherit;
    font-size: 0.85rem;
    font-weight: 600;
    background: #fff;
    border: 0;
    border-radius: 8px;
    cursor: pointer;
    transition: transform 0.12s;
}

.chip:hover {
    transform: translateY(-1px);
}

.chip img {
    width: 20px;
    height: 20px;
    object-fit: contain;
}

@media (max-width: 600px) {
    .card {
        padding: 22px 12px 18px;
    }
}
</style>