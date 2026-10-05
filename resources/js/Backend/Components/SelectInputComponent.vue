<template>
    <div :class="className">
        <label :for="id" class="form-label"
            >{{ label }} <sup v-if="isRequired">*</sup></label
        >
        <select
            class="select2 form-control form-select"
            :id="id"
            :multiple="isMultiple"
            v-model="model"
            placeholder="--Select--"
            :required="isRequired"
        >
            <!-- <option selected value="" disabled>-- Select --</option> -->
            <option
                :value="option.id"
                v-for="option in options"
                :key="option.id"
            >
                {{ option.name ? option.name : option.title }}
            </option>
        </select>
        <div class="text-danger">
            {{ error }}
        </div>
    </div>
</template>

<script>
export default {
    props: {
        id: {
            type: String,
            required: true,
        },
                className:{
            type: String,
            default:"mb-3 col-md-6"
        },
        label: {
            type: String,
            required: true,
        },
        error: {
            type: String,
            default: "",
        },
        isRequired: {
            type: Boolean,
            default: true,
        },
        isMultiple: {
            type: Boolean,
            default: false,
        },
        options: {
            type: Array,
            default: [],
        },
        modelValue: {
            default: "",
        },
    },
    data() {
        return {
            model: this.modelValue,
        };
    },
    mounted() {
        var thisFn = this;
        $(".select2").select2();
        $(".card-body").on(
            "change",
            "#" + thisFn.id,
            function (evt) {
                thisFn.$emit("update:model-value", $(evt.target).val());
                thisFn.$emit("change", $(evt.target).val());
            }
        );
    },
    emits: ['update:model-value', 'change'],
    watch: {
        modelValue(currentValue) {
            this.model = currentValue;
        },
    },
};
</script>

<style></style>
