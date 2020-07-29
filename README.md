# WPGraphql Redirection
A plugin that adds WPGraphQL to Redirection

- <a href="https://www.wpgraphql.com" target="_blank">WPGraphQL</a>
- <a href="https://redirection.me/" target="_blank">Redirection</a>


## Install
- Requires PHP 7.1+
- Requires WordPress 5.0+
- Requires WPGraphQL
- Requires Redirection (by John Godley)

## Example Usage
An example using Apollo Client and Express in NodeJS

    const REDIRECTION_QUERY = gql`
      query Redirection($matchUrl: String!) {
        redirections(
          where: { orderby: "position", matchUrl: $matchUrl, status: ENABLED }
          first: 10
        ) {
          nodes {
            url
            matchUrl
            regex
            status
            id
            databaseId
            position
            actionCode
            actionData
            actionType
          }
        }
      }
    `;

    export default (req, res) => {
      const client = new ApolloClient({
        ssrMode: true,
        link: new HttpLink({
          uri: Config.gqlUrl,
          fetch: fetch,
          credentials: "include",
          headers: {
            cookie: req.header("Cookie"),
            origin: FRONTEND_URL,
          },
        }),
        cache: new InMemoryCache(),
      });


        // Start Redirection lookup.
        const {
          data: {
            redirections: { nodes: redirections },
          },
        } = await client.query({
          query: REDIRECTION_QUERY,
          variables: { matchUrl: req.baseUrl + req.path },
        });

        if (redirections?.length > 0) {
          let newUrl = "";
          let code = 301;

          redirections.reverse().forEach((r) => {
            if (
              "url" === r.actionType &&
              r.actionData.replace(/^[\s\uFEFF\xA0\/]+|[\s\uFEFF\xA0\/]+$/g, "") !==
                r.matchUrl.replace(/^[\s\uFEFF\xA0\/]+|[\s\uFEFF\xA0\/]+$/g, "")
            ) {
              newUrl = r.actionData;
              code = r.actionCode;
            }
          });

          if (newUrl) {
            return res.redirect(code, newUrl);
          }
        }
        // End Redirection lookup.

      .. the rest of your page code
    }
