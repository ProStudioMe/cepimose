<script lang="ts" setup>
import SectionCard from "@/components/HostingerTools/SectionCard.vue";
import { useModal } from "@/composables";
import { SectionItem, ModalName, ToggleableSettingsData } from "@/types";
import { useSettingsStore, useGeneralStoreData } from "@/stores";
import {
  getAssetSource,
  isNewerVerison,
  getBaseUrl,
  translate,
} from "@/utils/helpers";
import ToolVersionCard from "@/components/HostingerTools/ToolVersionCard.vue";
import { computed, ref } from "vue";
import { storeToRefs } from "pinia";
import { kebabToCamel } from "@/utils/helpers";

const { fetchSettingsData, updateSettingsData, regenerateByPassCode } =
  useSettingsStore();

const { settingsData } = storeToRefs(useSettingsStore());
const { siteUrl } = useGeneralStoreData();

const WORDPRESS_UPDATE_LINK = getBaseUrl(location.href) + "update-core.php";

const isPageLoading = ref(false);

const maintenanceSection = computed(() => [
  {
    id: "maintenance-mode",
    title: translate("hostinger_tools_maintenance_mode"),
    description: translate("hostinger_tools_disable_public_access"),
    isVisible: true,
    toggleValue: settingsData.value?.maintenanceMode,
  },
  {
    id: "bypass-link",
    title: translate("hostinger_tools_bypass_link"),
    description: translate("hostinger_tools_skip_link_maintenance_mode"),
    sideButton: {
      text: translate("hostinger_tools_reset_link"),
      onClick: () => {
        openModal(
          ModalName.ByPassLinkResetModal,
          {
            data: {
              onConfirm: () => {
                regenerateByPassCode();
              },
            },
          },
          { isLG: true }
        );
      },
    },
    copyLink:
      settingsData.value?.bypassCode &&
      // @ts-ignore
      `${siteUrl}/?bypass_code=${settingsData.value.bypassCode}`,
  },
]);

const securitySection = computed(() => [
    {
        id: "disable-xml-rpc",
        title: translate("hostinger_tools_disable_xml_rpc"),
        description: translate("hostinger_tools_xml_rpc_description"),
        isVisible: true,
        toggleValue: settingsData.value?.disableXmlRpc,
    },
    {
        id: "disable-authentication-password",
        title: translate("hostinger_tools_disable_authentication_password"),
        description: translate("hostinger_tools_authentication_password_description"),
        isVisible: true,
        toggleValue: settingsData.value?.disableAuthenticationPassword,
    },
]);

const redirectsSection = computed(() => {
  let sections = [
    {
      id: "force-https",
      title: translate("hostinger_tools_force_https"),
      description: translate("hostinger_tools_force_https_description"),
      isVisible: true,
      toggleValue: settingsData.value?.forceHttps,
    },
  ];

  sections.push({
    id: "force-www",
    title: translate("hostinger_tools_force_www"),
    description: !settingsData.value?.isEligibleWwwRedirect
      ? translate("hostinger_tools_force_www_description_not_available")
      : translate("hostinger_tools_force_www_description"),
    isVisible: !!settingsData.value?.isEligibleWwwRedirect,
    toggleValue: settingsData.value?.forceWww,
  });

  return sections.filter((section) => section.isVisible);
});

const llmsSection = computed(() => [
  {
    id: "enable-llms-txt",
    title: translate("hostinger_tools_enable_llms_txt"),
    description: translate("hostinger_tools_llms_txt_description"),
    isVisible: true,
    toggleValue: settingsData.value?.enableLlmsTxt,
  },
]);

const { openModal } = useModal();

const isWordPressUpdateDisplayed = computed(() => {
  if (!settingsData.value) {
    return false;
  }

  return isNewerVerison({
    currentVersion: settingsData.value.currentWpVersion,
    newVersion: settingsData.value.newestWpVersion,
  });
});

const isPhpUpdateDisplayed = computed(() => {
  if (!settingsData.value) {
    return false;
  }

  return isNewerVerison({
    currentVersion: settingsData.value.phpVersion,
    newVersion: "8.2", // Hardcoded for now
  });
});

const isHostingerPlatform = computed(() => {
    return parseInt(hostinger_tools_data.hplatform) > 0;
});

const phpVersionCardText = computed(() => {
    if( !isHostingerPlatform.value ) {
        return `${translate("hostinger_tools_update_to")} 8.2 ${translate("hostinger_tools_update_to_recommended")}`;
    }

    return `${translate("hostinger_tools_update_to")} 8.2`;
});

const phpVersionCard = computed(() => ({
  title: translate("hostinger_tools_php_version"),
  description: isPhpUpdateDisplayed.value
    ? translate("hostinger_tools_php_version_description")
    : translate("hostinger_tools_running_latest_version"),
  toolImageSrc: getAssetSource("images/icons/icon-php.svg"),
  version: settingsData.value?.phpVersion,
  buttonShown: isHostingerPlatform.value,
  actionButton: isPhpUpdateDisplayed.value
    ? {
        text: phpVersionCardText.value,
        onClick: () => {
          window.open(
            `https://auth.${resellerLocale.value}/login?r=/section/php-configuration/domain/${location.host}`,
            "_blank"
          );
        },
      }
    : undefined,
}));


const resellerLocale = computed(() => {
  {
    const { pluginUrl } = useGeneralStoreData();

    return pluginUrl.match(/^[^/]+/)![0] || "hostinger.com";
  }
});

const wordPressVersionCard = computed(() => ({
  title: translate("hostinger_tools_wordpress_version"),
  description: isWordPressUpdateDisplayed.value
    ? translate("hostinger_tools_update_to_wordpress_version_description")
    : translate("hostinger_tools_running_latest_version"),
  toolImageSrc: getAssetSource("images/icons/icon-wordpress-light.svg"),
  version: settingsData.value?.currentWpVersion,
  buttonShown: true,
  actionButton: isWordPressUpdateDisplayed.value
    ? {
        text: `${translate("hostinger_tools_update_to")} ${settingsData.value?.newestWpVersion}`,
        onClick: () => {
          window.location.href = WORDPRESS_UPDATE_LINK; // redirects to wp update page in wp admin
        },
      }
    : undefined,
}));

const onSaveSection = (value: boolean, item: SectionItem) => {
  const IMPORTANT_SECTIONS = ["disable-xml-rpc"];

  const isTurnedOn = value === false;

  if (IMPORTANT_SECTIONS.includes(item.id) && isTurnedOn) {
    openModal(
      ModalName.XmlSecurityModal,
      {
        data: {
          onConfirm: () => {
            onUpdateSettings(value, item);
          },
        },
      },
      { isLG: true }
    );

    return;
  }

  onUpdateSettings(value, item);
};

const onUpdateSettings = (value: boolean, item: SectionItem) => {
  if (!settingsData.value) return;

  const id = kebabToCamel(item.id) as keyof ToggleableSettingsData;

  settingsData.value[id] = value;

  updateSettingsData(settingsData.value);
};


(async () => {
  isPageLoading.value = true;
  await fetchSettingsData();
  isPageLoading.value = false;
})();
</script>

<template>
  <div v-if="settingsData">
    <div class="hostinger-tools__tool-version-cards">
      <ToolVersionCard
        :is-loading="isPageLoading"
        v-bind="wordPressVersionCard"
        class="h-mr-16"
      />
      <ToolVersionCard
          :is-loading="isPageLoading"
          v-bind="phpVersionCard"
      />
    </div>
    <div>
      <SectionCard
        :is-loading="isPageLoading"
        @save-section="onSaveSection"
        :title="translate('hostinger_tools_maintenance')"
        :section-items="maintenanceSection"
      />
      <SectionCard
        :is-loading="isPageLoading"
        @save-section="onSaveSection"
        :title="translate('hostinger_tools_security')"
        :section-items="securitySection"
      />
      <SectionCard
        :is-loading="isPageLoading"
        @save-section="onSaveSection"
        :title="translate('hostinger_tools_redirects')"
        :section-items="redirectsSection"
      />
      <SectionCard
        :is-loading="isPageLoading"
        @save-section="onSaveSection"
        :title="translate('hostinger_tools_llms')"
        :section-items="llmsSection"
      />
    </div>
  </div>
</template>

<style lang="scss">
.hostinger-tools {
  &__tool-version-cards {
    display: flex;
    width: 100%;

    @media (max-width: 590px) {
      flex-direction: column;
    }
  }
}
</style>
