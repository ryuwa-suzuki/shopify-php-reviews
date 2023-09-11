import {
  Icon,
  IndexTable,
  LegacyCard,
  Thumbnail
} from '@shopify/polaris';
import React from 'react';
import dayjs from "dayjs";
import { ImageMajor } from "@shopify/polaris-icons";

export function ReviewsIndex ({reviews, loading}) {
  const resourceName = {
    singular: 'reviews',
    plural: 'reviews',
  };

  const rowMarkup = reviews.map(
    (
      {id, rating, comment, created_at, product, name},
      index,
    ) => {

      let stars = '';
      for(let i = 0; i < 5; i++) {
        if (i < rating) {
          stars += '★';
        } else {
          stars += '☆';
        }
      }

      return (
        <IndexTable.Row
          id={id}
          key={id}
          position={index}
        >
          <IndexTable.Cell>
            {stars}
          </IndexTable.Cell>
          <IndexTable.Cell>
            <Thumbnail
              source={product?.image?.src || ImageMajor}
              alt="placeholder"
              color="base"
              size="small"
            />
            {product?.handle || 'Delete Product'}
          </IndexTable.Cell>
          <IndexTable.Cell>
            {name}
          </IndexTable.Cell>
          <IndexTable.Cell>
            <div
            style={{
              whiteSpace: 'wrap'
              }}>
              {comment}
            </div>
          </IndexTable.Cell>
          <IndexTable.Cell>{dayjs(created_at).format("MMMM D, YYYY")}</IndexTable.Cell>
        </IndexTable.Row>
      );
    },
  );

  return (
    <LegacyCard>
      <IndexTable
        resourceName={resourceName}
        itemCount={reviews.length}
        selectable={false}
        headings={[
          {title: 'Raiting'},
          {title: 'Product'},
          {title: 'Name'},
          {title: 'Review'},
          {title: 'Date'},
        ]}
        loading={loading}
      >
        {rowMarkup}
      </IndexTable>
    </LegacyCard>
  );
}
