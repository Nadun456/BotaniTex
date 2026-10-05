<template>
    <footer class="test-footer">
        <div class="footer-content">
            <div class="company">
                <p>
                    Demo Company is a sample business website created for
                    demonstration and testing purposes.
                </p>
            </div>

            <div>
                <h3>Explore</h3>
                <Link :href="route('home')">Home</Link>
                <Link :href="route('product')">Products</Link>
                <Link :href="route('make')">Makes</Link>
                <Link :href="route('model')">Models</Link>
            </div>

            <div>
                <h3>Company</h3>
                <Link :href="route('about-us')">About Us</Link>
                <Link :href="route('faq')">FAQ</Link>
                <Link :href="route('contact-us')">Contact Us</Link>
            </div>

            <div>
                <h3>Contact</h3>
                <p>123 Main Street</p>
                <p>Colombo, Sri Lanka</p>
                <p>+94 700 000 000</p>
                <p>info@democompany.com</p>
            </div>
        </div>

        <div class="copyright">
            © {{ new Date().getFullYear() }} Demo Company. All rights reserved.
        </div>
    </footer>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";

export default {
    components: {
        Link,
    },
};
</script>

<style scoped>
.test-footer {
    padding: 40px 8% 16px;
    color: white;
    background: #222;
}

.footer-content {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 30px;
}

.company img {
    width: 160px;
    background: white;
}

.footer-content h3 {
    margin-bottom: 14px;
}

.footer-content a {
    display: block;
    margin-bottom: 10px;
    color: #ddd;
    text-decoration: none;
}

.footer-content a:hover {
    color: white;
    text-decoration: underline;
}

.copyright {
    margin-top: 30px;
    padding-top: 16px;
    border-top: 1px solid #555;
    text-align: center;
    color: #ccc;
}

@media (max-width: 700px) {
    .footer-content {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 450px) {
    .footer-content {
        grid-template-columns: 1fr;
    }
}
</style>