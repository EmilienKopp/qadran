<script lang="ts">
  import PrimaryButton from "$components/Buttons/PrimaryButton.svelte";
  import SectionCard from "$components/UI/SectionCard.svelte";
  import Dialog from "$components/Modals/Dialog.svelte";
  import { Form } from "@inertiajs/svelte";
  import DatePicker from "$components/DataInput/DatePicker.svelte";
  import Select from "$components/DataInput/Select.svelte";
  import Input from "$components/DataInput/Input.svelte";


  let createTokenDialog: Dialog;
</script>


<SectionCard
  title="Access Tokens"
  subtitle="Manage your API access tokens for secure interactions with external services."
>
  {#snippet content()}
      <!-- Generate new -->
      <PrimaryButton
        onclick={createTokenDialog?.open}>Generate New</PrimaryButton>
  {/snippet}
</SectionCard>

<Dialog
  bind:this={createTokenDialog}
>
  <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
    Create New Access Token
  </h3>
  <div class="mt-4">
    <Form method="POST" >
        <div class="flex items-center gap-6">
          <Input
            id="name"
            name="name"
            type="text"
            required
          />
  
          <Select>
            <option value="30">30 Days</option>
            <option value="60">60 Days</option>
            <option value="90">90 Days</option>
            <option value="180">180 Days</option>
            <option value="365">1 Year</option>
            <option value="0">No Expiration</option>
          </Select>
        </div>

      <div class="mt-4 flex justify-end">
        <PrimaryButton type="submit">Create Token</PrimaryButton>
      </div>
    </Form>
  </div>
</Dialog>