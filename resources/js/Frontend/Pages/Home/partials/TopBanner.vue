<template>
    <section class="banner">
        <video autoplay muted loop playsinline>
            <source
                src="/images/Bikes/Banner-images/5198907-uhd_4096_2160_25fps.mp4"
                type="video/mp4"
            />
        </video>

        <div class="content">
            <h1>Motorcycle Parts</h1>
            <p>Find the parts you need for your bike.</p>

            <div class="categories">
                <div>
                    <img src="/images/Bikes/Banner-images/side1.png" alt="Fuel System" />
                    <h2>Fuel System</h2>
                </div>

                <div>
                    <img src="/images/Bikes/Banner-images/side2.png" alt="Filters" />
                    <h2>Filters</h2>
                </div>

                <div>
                    <img src="/images/Bikes/Banner-images/side3.png" alt="Oils and Fluids" />
                    <h2>Oils &amp; Fluids</h2>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.banner {
    position: relative;
    padding: 40px;
    color: white;
    background: #222;
}

video {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0.4;
}

.content {
    position: relative;
    text-align: center;
}

.categories {
    display: flex;
    justify-content: center;
    gap: 24px;
    margin-top: 30px;
}

.categories img {
    width: 100px;
    height: 100px;
    object-fit: contain;
}

@media (max-width: 600px) {
    .categories {
        flex-direction: column;
        align-items: center;
    }
}
</style>