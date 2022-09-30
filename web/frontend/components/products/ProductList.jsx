import { useNavigate } from '@shopify/app-bridge-react';
import { DiamondAlertMajor, ImageMajor } from '@shopify/polaris-icons';
import {
    Card,
    Icon,
    IndexTable,
    Stack,
    TextStyle,
    Thumbnail,
    UnstyledLink,
} from '@shopify/polaris';

/* useMedia is used to support multiple screen sizes */
// import { useMedia } from '@shopify/react-hooks';

/* dayjs is used to capture and format the date a QR code was created or modified */
// import dayjs from 'dayjs';

export default function ProductList({ products, loading }) {
    const navigate = useNavigate();
// console.log(products)
    /* Check if screen is small */
    //   const isSmallScreen = useMedia('(max-width: 640px)');

    const resourceName = { singular: 'Product', plural: 'Products', };

    return (
        <Card>
            <IndexTable
                resourceName={resourceName}
                itemCount={products.length}
                headings={[
                    { title: 'ID', },
                    { title: 'Thumbnail', hidden: false },
                    { title: 'Title' },
                    { title: 'Created At' },
                ]}
                selectable={false}
                loading={loading}
            >
            {console.log(products.length)}
                { products.map(({id, title, image, created_at }, index) => (
                    <IndexTable.Row
                        id={id}
                        key={id}
                        position={index}
                        onClick={() => {
                            navigate(`/products/${id}`);
                        }}
                    >
                        <IndexTable.Cell>{id}</IndexTable.Cell>

                        <IndexTable.Cell>
                            <Thumbnail
                                source={(image ? image.src : null) || ImageMajor}
                                alt="placeholder"
                                color="base"
                                size="small"
                            />
                        </IndexTable.Cell>

                        <IndexTable.Cell>
                            <UnstyledLink data-primary-link url={`/products/${id}`}>
                                {title}
                                {/* {truncate(title, 25)} */}
                            </UnstyledLink>
                        </IndexTable.Cell>

                        <IndexTable.Cell>
                            {created_at}
                            {/* {dayjs(created_at).format("MMMM D, YYYY")} */}
                        </IndexTable.Cell>
                    </IndexTable.Row>
                ))}
            </IndexTable>
        </Card>
    );
}

function truncate(str, n) {
  return str.length > n ? str.substr(0, n - 1) + "…" : str;
}
