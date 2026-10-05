<template>
    <header>
        <div class="top-bar">
            <a href="tel:+94700000000">
                Hotline: (+94) 700 000 000
            </a>
        </div>

        <nav class="navbar">
            <Link :href="route('home')" class="logo">
                <!-- <img src="/images/Bikes/Logos/demologo.png" alt="DEMO COMPANY" /> -->
            </Link>

            <button class="menu-button" @click="menuOpen = !menuOpen">
                {{ menuOpen ? "Close" : "Menu" }}
            </button>

            <div class="menu" :class="{ open: menuOpen }">
                <Link :href="route('home')" @click="menuOpen = false">
                    Home
                </Link>

               

                <Link :href="route('about-us')" @click="menuOpen = false">
                    About Us
                </Link>

                <Link :href="route('faq')" @click="menuOpen = false">
                    FAQ
                </Link>

                <Link :href="route('contact-us')" @click="menuOpen = false">
                    Contact Us
                </Link>
            </div>
        </nav>
    </header>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";

export default {
    components: { Link },

    data() {
        return {
            menuOpen: false,
        };
    },
};
</script>

<style scoped>
.top-bar {
    padding: 8px 5%;
    background: #f2f2f2;
    text-align: right;
}

.top-bar a {
    color: #222;
    text-decoration: none;
}

.navbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 5%;
    background: white;
    box-shadow: 0 2px 6px #ddd;
}

.logo img {
    width: 150px;
    height: 50px;
    object-fit: contain;
}

.menu {
    display: flex;
    gap: 20px;
}

.menu a {
    color: #222;
    text-decoration: none;
}

.menu a:hover {
    color: #084e91;
}

.menu-button {
    display: none;
}

@media (max-width: 768px) {
    .menu-button {
        display: block;
        padding: 8px 12px;
    }

    .menu {
        display: none;
        position: absolute;
        top: 100px;
        right: 0;
        left: 0;
        flex-direction: column;
        gap: 0;
        padding: 12px 5%;
        background: white;
        box-shadow: 0 4px 6px #ddd;
    }

    .menu.open {
        display: flex;
    }

    .menu a {
        padding: 10px 0;
    }
}
</style>