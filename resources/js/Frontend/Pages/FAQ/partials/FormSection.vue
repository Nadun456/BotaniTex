```vue
<template>
    <section>
        <div class="text-center mt-5 mb-5">
            <h1 class="fw-medium">FAQs</h1>
        </div>

        <div class="container faq accordion mb-5" id="accordionExample">

            <!-- FAQ 1 -->
            <div class="accordion-item rounded-3">
                <h2 class="accordion-header">
                    <button
                        class="accordion-button rounded-3 fw-semibold fs-6"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapseOne"
                        aria-expanded="true"
                        aria-controls="collapseOne"
                    >
                        What is Demo Company?
                    </button>
                </h2>

                <div
                    id="collapseOne"
                    class="accordion-collapse collapse show"
                    data-bs-parent="#accordionExample"
                >
                    <hr class="my-0 mx-3" />

                    <div class="accordion-body mt-3 fs-6">
                        Demo Company is a sample business created for testing
                        and demonstration purposes. We provide various sample
                        products and services to our customers.
                    </div>
                </div>
            </div>


            <!-- FAQ 2 -->
            <div class="accordion-item rounded-3 my-3">
                <h2 class="accordion-header">
                    <button
                        class="accordion-button collapsed rounded-3 fw-semibold fs-6"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapseTwo"
                        aria-expanded="false"
                        aria-controls="collapseTwo"
                    >
                        How can I place an order?
                    </button>
                </h2>

                <div
                    id="collapseTwo"
                    class="accordion-collapse collapse"
                    data-bs-parent="#accordionExample"
                >
                    <hr class="my-0 mx-3" />

                    <div class="accordion-body mt-3 fs-6">
                        You can place an order by selecting a product from our
                        website and completing the order form with your required
                        information.
                    </div>
                </div>
            </div>


            <!-- FAQ 3 -->
            <div class="accordion-item rounded-3 my-3">
                <h2 class="accordion-header">
                    <button
                        class="accordion-button collapsed rounded-3 fw-semibold fs-6"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapseThree"
                        aria-expanded="false"
                        aria-controls="collapseThree"
                    >
                        How long does delivery take?
                    </button>
                </h2>

                <div
                    id="collapseThree"
                    class="accordion-collapse collapse"
                    data-bs-parent="#accordionExample"
                >
                    <hr class="my-0 mx-3" />

                    <div class="accordion-body mt-3 fs-6">
                        Standard delivery usually takes between 2 and 5
                        business days depending on the delivery location.
                    </div>
                </div>
            </div>


            <!-- FAQ 4 -->
            <div class="accordion-item rounded-3 my-3">
                <h2 class="accordion-header">
                    <button
                        class="accordion-button collapsed rounded-3 fw-semibold fs-6"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapseFour"
                        aria-expanded="false"
                        aria-controls="collapseFour"
                    >
                        What payment methods are available?
                    </button>
                </h2>

                <div
                    id="collapseFour"
                    class="accordion-collapse collapse"
                    data-bs-parent="#accordionExample"
                >
                    <hr class="my-0 mx-3" />

                    <div class="accordion-body mt-3 fs-6">
                        Demo Company accepts several payment methods including
                        cash payments, bank transfers, and online payments.
                    </div>
                </div>
            </div>


            <!-- FAQ 5 -->
            <div class="accordion-item rounded-3 my-3">
                <h2 class="accordion-header">
                    <button
                        class="accordion-button collapsed rounded-3 fw-semibold fs-6"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapseFive"
                        aria-expanded="false"
                        aria-controls="collapseFive"
                    >
                        Can I return a product?
                    </button>
                </h2>

                <div
                    id="collapseFive"
                    class="accordion-collapse collapse"
                    data-bs-parent="#accordionExample"
                >
                    <hr class="my-0 mx-3" />

                    <div class="accordion-body mt-3 fs-6">
                        Yes. Products can be returned according to our sample
                        return policy. Please contact our support team for
                        further assistance.
                    </div>
                </div>
            </div>


            <!-- FAQ 6 -->
            <div class="accordion-item rounded-3 my-3">
                <h2 class="accordion-header">
                    <button
                        class="accordion-button collapsed rounded-3 fw-semibold fs-6"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapseSix"
                        aria-expanded="false"
                        aria-controls="collapseSix"
                    >
                        How can I contact customer support?
                    </button>
                </h2>

                <div
                    id="collapseSix"
                    class="accordion-collapse collapse"
                    data-bs-parent="#accordionExample"
                >
                    <hr class="my-0 mx-3" />

                    <div class="accordion-body mt-3 fs-6">
                        You can contact our customer support team through the
                        contact form, email, or telephone during our normal
                        business hours.
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
.faq .accordion {
    --bs-accordion-active-bg: #fff;
    --bs-accordion-active-color: #000;
    --bs-accordion-btn-focus-box-shadow: none;
}

.faq .accordion-button {
    font-size: 14px;
}

.faq .accordion-button.collapsed {
    border: 1px solid gray;
}

.faq .accordion .accordion-item:has(.accordion-button:not(.collapsed)) {
    border: 1px solid #000000;
}

.faq .accordion-collapse {
    transition: all 0.3s;
}

.faq .accordion-button:not(.collapsed) {
    box-shadow: none;
}

.faq .accordion-button::after {
    background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 144L48 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l144 0 0 144c0 17.7 14.3 32 32 32s32-14.3 32-32l0-144 144 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-144 0 0-144z"/></svg>');
    transition-duration: 0.4s;
    font-size: 5px;
}

.faq .accordion-button:not(.collapsed)::after {
    background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M432 256c0 17.7-14.3 32-32 32L48 288c-17.7 0-32-14.3-32-32s14.3-32 32-32l352 0c17.7 0 32 14.3 32 32z"/></svg>');
}
</style>
```
