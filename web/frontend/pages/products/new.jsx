import { Frame, Layout, Page } from '@shopify/polaris';
import { TitleBar } from '@shopify/app-bridge-react';

import ProductForm from '../../components/products/ProductForm';
import SideBar from '../../components/layouts/SideBar';

export default function ManageCode() {
    const breadcrumbs = [{ content: 'Products', url: '/' }];

    return (
        <Frame navigation={SideBar()}>
            <Page>
                <Layout title="Create new Product">
                    <TitleBar
                        title="Create new Product"
                        breadcrumbs={breadcrumbs}
                        primaryAction={null}
                    />

                    <Layout.Section>
                        <ProductForm />
                    </Layout.Section>
                </Layout>
            </Page>
        </Frame>
    );
}
