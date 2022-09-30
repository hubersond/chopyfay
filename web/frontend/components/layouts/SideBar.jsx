import { useNavigate } from "@shopify/app-bridge-react";
import { Navigation, } from "@shopify/polaris";
import {
    AddProductMajor,
    ArrowLeftMinor,
    HomeMajor,
    OrdersMajor,
    ConversationMinor,
    ProductsMinor,
} from '@shopify/polaris-icons';

export default function SideBar() {
    const navigate = useNavigate();

    const navItems = [
        { label: 'Products', icon: HomeMajor, onClick: () => navigate('/') },
        { label: 'Create product', icon: AddProductMajor, onClick: () => navigate('/products/new') },
    ];

    return (
        <Navigation location="/">
            <Navigation.Section
                items={[{ label: 'Back to Shopify', icon: ArrowLeftMinor, }]}
            />

            <Navigation.Section separator items={navItems} />
        </Navigation>
    );
}
