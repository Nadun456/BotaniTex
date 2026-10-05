```vue
<template>
    <section class="container">

        <!-- About Us -->
        <div class="text-center mt-5 mb-5">
            <h1 class="fw-medium mb-4">About Us</h1>

            <p class="fs-6">
                <span class="fw-bold">ABC SPARE PARTS (PVT) LTD.</span>
                is a newly established company founded in 2025 by a team of
                experienced professionals in the motorcycle spare parts industry.
                Our company is focused on providing reliable and affordable spare
                parts to customers locally and internationally.
            </p>

            <p class="fs-6">
                We work with trusted suppliers and manufacturers to provide
                high-quality products. Our goal is to build strong relationships
                with our customers by providing excellent products, competitive
                prices, and reliable customer service.
            </p>
        </div>


        <!-- Mission -->
        <div class="container mb-5 mt-5">
            <div class="row align-items-center justify-content-center">

                <div class="col-md-6">
                    <div class="row">

                        <div class="col-3">
                            <h1>Mission</h1>
                        </div>

                        <div class="col-3"></div>

                        <p>
                            Our mission is to provide high-quality motorcycle spare
                            parts at affordable prices while delivering excellent
                            customer service. We are committed to maintaining
                            product quality, reliability, and customer satisfaction.
                        </p>

                    </div>
                </div>

                <div class="col-md-6 vision-img">
                    <img
                        src="https://via.placeholder.com/740x500"
                        alt="Dummy Mission Image"
                        class="w-100 h-100 object-fit-cover"
                    />
                </div>

            </div>
        </div>


        <hr>


        <!-- Vision -->
        <div class="container mb-5 mt-5">
            <div class="row align-items-center justify-content-center">

                <div class="col-md-6 vision-img">
                    <img
                        src="https://via.placeholder.com/740x500"
                        alt="Dummy Vision Image"
                        class="w-100 h-100 object-fit-cover"
                    />
                </div>

                <div class="col-md-6">

                    <div class="row">

                        <div class="col-3">
                            <h1>Vision</h1>
                        </div>

                        <div class="col-3"></div>

                        <p>
                            Our vision is to become a trusted spare parts
                            supplier by providing quality products, competitive
                            prices, and dependable services. We aim to continuously
                            improve our products and services to meet the changing
                            needs of our customers.
                        </p>

                    </div>

                </div>

            </div>
        </div>

    </section>
</template>


<script>
export default {};
</script>


<style scoped>

.box {
    width: 400px;
    margin: 10px auto;
    position: relative;
    overflow: hidden;
    height: 300px;
}

.box .eye-icon {
    width: 100%;
    height: auto;
}

.content {
    color: #fff;
    background: #000;
    position: absolute;
    top: 100%;
    left: 0%;
    width: 100%;
    height: 300px;
    padding: 20px;
    box-sizing: border-box;
    transition: all 0.5s;
    align-content: center;
}

.box:hover .content {
    top: 0;
}

.vision-img {
    width: 333px;
}

</style>
```
