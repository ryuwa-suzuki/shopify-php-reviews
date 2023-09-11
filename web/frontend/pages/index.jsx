import { TitleBar, Loading } from "@shopify/app-bridge-react";
import { Page, Layout, Card, SkeletonBodyText } from '@shopify/polaris';
import { ReviewsIndex } from '../components';
import { useAppQuery } from "../hooks";


export default function HomePage () {
  const {
      data: reviews,
      isRefetching,
      isLoading
    } = useAppQuery({
      url: "/api/reviews",
    });

  const loadingMarkup = isLoading ? (
    <Card sectioned>
      <Loading />
      <SkeletonBodyText />
    </Card>
  ) : null;

  const reviewsMarkup = reviews?.length ? (
    <ReviewsIndex reviews={reviews} loading={isRefetching} />
  ) : null;

  return (
    <Page>
      <TitleBar
        title="Reviews"
      ></TitleBar>
      <Layout>
        <Layout.Section>
          {loadingMarkup}
          {reviewsMarkup}
        </Layout.Section>
      </Layout>
    </Page>
  );
}
