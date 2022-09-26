import { TitleBar } from "@shopify/app-bridge-react";
import {
    Layout,
    Page,
} from "@shopify/polaris";

import { AppBanner, ProductsCard } from "../components";

export default function HomePage() {
    return (
        <Page narrowWidth>
            <TitleBar title="App name" primaryAction={null} />

            <Layout>
                <Layout.Section>
                    <AppBanner />
                </Layout.Section>

                <Layout.Section>
                    <ProductsCard />
                </Layout.Section>
            </Layout>
        </Page>
    );
}
