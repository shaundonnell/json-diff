export type PayloadImage = {
    id: number;
    position: number;
    url: string;
};

export type PayloadVariant = {
    id: number;
    sku: string;
    barcode: string;
    image_id: number;
    inventory_quantity: number;
};

export type Payload = {
    id: number;
    title: string;
    description: string;
    images: PayloadImage[];
    variants: PayloadVariant[];
};

export const payload1: Payload = {
    id: 432232523,
    title: 'Syncio T-Shirt',
    description:
        '<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.</p><p>Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus,</p>',
    images: [
        {
            id: 26372,
            position: 1,
            url: 'https://picsum.photos/id/100/200/200',
        },
        {
            id: 23445,
            position: 2,
            url: 'https://picsum.photos/id/201/200/200',
        },
        {
            id: 34566,
            position: 3,
            url: 'https://picsum.photos/id/300/200/200',
        },
        {
            id: 33253,
            position: 4,
            url: 'https://picsum.photos/id/401/200/200',
        },
        {
            id: 56353,
            position: 5,
            url: 'https://picsum.photos/id/500/200/200',
        },
    ],
    variants: [
        {
            id: 433232,
            sku: 'SKU-II-10',
            barcode: 'BAR_CODE_230',
            image_id: 26372,
            inventory_quantity: 12,
        },
        {
            id: 231544,
            sku: 'SKU-II-20',
            barcode: 'B231342313',
            image_id: 23445,
            inventory_quantity: 2,
        },
        {
            id: 323245,
            sku: 'SKU-II-1O',
            barcode: 'BACDSDE_0',
            image_id: 34566,
            inventory_quantity: 8,
        },
        {
            id: 323445,
            sku: 'SKU-II-1o',
            barcode: 'AR_CO23DE_23',
            image_id: 33253,
            inventory_quantity: 0,
        },
    ],
};

export const payload2: Payload = {
    id: 432232523,
    title: 'Syncio T-Shirt',
    description:
        '<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque. penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.</p><p>Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus,</p>',
    images: [
        {
            id: 26372,
            position: 1,
            url: 'https://picsum.photos/id/100/200/200',
        },
        {
            id: 33245,
            position: 2,
            url: 'https://picsum.photos/id/200/200/200',
        },
        {
            id: 56353,
            position: 3,
            url: 'https://picsum.photos/id/300/200/200',
        },
        {
            id: 33213,
            position: 4,
            url: 'https://picsum.photos/id/400/200/200',
        },
        {
            id: 34546,
            position: 5,
            url: 'https://picsum.photos/id/500/200/200',
        },
        {
            id: 34566,
            position: 3,
            url: 'https://picsum.photos/id/600/200/200',
        },
    ],
    variants: [
        {
            id: 33232,
            sku: 'SKU-II-10',
            barcode: 'BAR_CODE_230',
            image_id: 34566,
            inventory_quantity: 12,
        },
        {
            id: 231544,
            sku: 'SKU-II-20',
            barcode: 'B231342313',
            image_id: 56353,
            inventory_quantity: 2,
        },
        {
            id: 323245,
            sku: 'SKU-II-10',
            barcode: 'BACDSDE_O',
            image_id: 26372,
            inventory_quantity: 8,
        },
        {
            id: 323445,
            sku: 'SKU-II-1о',
            barcode: 'AR_CO23DE_23',
            image_id: 33213,
            inventory_quantity: 0,
        },
    ],
};
