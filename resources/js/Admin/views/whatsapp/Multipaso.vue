<script setup>
  const props = defineProps(['steps', 'value'])
  const emits = defineEmits(['update:value'])
</script>

<template>
  <div class="card">
    <div class="card-body d-flex flex-column" style="height: 82vh;">
      <div class="row multistep-steps" style="flex: 0 0 auto;">
        <div
          @click="()=>step.enabled ? $emit('input', step.index) : ''"
          v-for="step in steps"
          :key="step.index"
          class="col multistep-item"
          :class="{
            'multistep-item--active': step.index == value,
            'multistep-item--disabled': !step.enabled
          }">
          <div class="multistep-item__index">{{ step.index }}</div>
          <div class="multistep-item__title">{{ step.title }}</div>
        </div>
      </div>

      <slot></slot>
    </div>
  </div>
</template>

<style lang="scss">
  .multistep-item {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-flow: column nowrap;
    cursor: pointer;

    &__index {
      color: #fff;
      font-size: .9rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 22.5px;
      height: 22.5px;
      margin-bottom: .5rem;
      border-radius: 50%;
      background-color: rgb(161, 159, 159);
    }
    &__title {
      font-size: .9rem;
    }
  }

  .multistep-item--active {
    @extend .multistep-item;
    .multistep-item__index {
      background-color: var(--bs-primary)
    }
    .multistep-item__title {
      font-weight: 500;
    }
  }

  .multistep-item--disabled {
    cursor: not-allowed;
    opacity: .5;
  }
</style>