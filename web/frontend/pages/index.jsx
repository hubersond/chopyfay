import { Loading, useNavigate, TitleBar } from "@shopify/app-bridge-react";
import {
    Card,
    EmptyState,
    Frame,
    Layout,
    Page,
    SkeletonBodyText,
} from "@shopify/polaris";

import { useAppQuery } from '../hooks';

import SideBar from '../components/layouts/SideBar';
import { useState } from "react";
import ProductList from "../components/products/ProductList";

export default function HomePage() {
    const [isLoading, setIsLoading] = useState(true);
    const [selected, setSelected] = useState([]);

    const navigate = useNavigate();

    // const breadcrumbs = [{ content: 'Products', url: '/' }];

    const isRefetching = false;

    const pageActions = {
        newProduct: {
            content: 'Create product',
            onAction: () => navigate('/products/new'),
        },
    };

    const {
        data,
        refetch: refetchProductCount,
        isLoading: isLoadingCount,
        isRefetching: isRefetchingCount,
    } = useAppQuery({
        url: '/api/products',
        reactQueryOptions: {
            onSuccess: () => {
                setIsLoading(false);
                // console.log(getProductList());
            },
        },
    });

    return (
        <Frame navigation={SideBar()}>
            <Page>
                <Layout>
                    <TitleBar title="Products" primaryAction={null} />

                    <Layout.Section>
                        { isLoading &&
                        <Card sectioned>
                            <Loading />
                            <SkeletonBodyText lines={9}/>
                        </Card>}

                        { (!isLoading && !data) &&
                            <Card sectioned>
                                <EmptyState
                                    heading="Create & view products"
                                    action={pageActions.newProduct}
                                    image="https://cdn.shopify.com/s/files/1/0262/4071/2726/files/emptystate-files.png"
                                >
                                    <p> <b>Go ahead! Add some products!</b> </p>
                                    <p> <i>No body is gonna buy them though 😉</i> </p>
                                </EmptyState>
                            </Card>
                        }

                        { (!isLoading && data) &&
                            <ProductList products={data.products} loading={isLoading} />
                        }
                    </Layout.Section>
                </Layout>
            </Page>
        </Frame>
    );
}
