[% extend base %]

[% begin styles %]
   <style>
        body {
            height: 100%;
            margin: 0;
            width: 100%;
            overflow: hidden;
        }

        #graphiql {
            height: 100vh;
        }
    </style>
    <link rel="stylesheet" href="/vendor/graphql/graphiql.css" />
[% end %]

[% begin js %]
    <script type="application/javascript" src="/vendor/graphql/react/react-15.0.1.min.js"></script>
    <script type="application/javascript" src="/vendor/graphql/react/react-dom-15.0.1.min.js"></script>
    <script type="application/javascript" src="/vendor/graphql/react/fetch.min.js"></script>
    <script type="application/javascript" src="/vendor/graphql/graphiql.min.js"></script>
[% end %]

[% begin body %]
    <div id="graphiql">Loading...</div>
    <script>

        /**
        * This GraphiQL example illustrates how to use some of GraphiQL's props
        * in order to enable reading and updating the URL parameters, making
        * link sharing of queries a little bit easier.
        *
        * This is only one example of this kind of feature, GraphiQL exposes
        * various React params to enable interesting integrations.
        */

                // Parse the search string to get url parameters.
        var search     = window.location.search;
        var parameters = {};
        search.substr(1).split('&').forEach(function (entry) {
            var eq = entry.indexOf('=');
            if (eq >= 0) {
                parameters[decodeURIComponent(entry.slice(0, eq))] =
                        decodeURIComponent(entry.slice(eq + 1));
            }
        });

        // if variables was provided, try to format it.
        if (parameters.variables) {
            try {
                parameters.variables =
                        JSON.stringify(JSON.parse(parameters.variables), null, 2);
            } catch (e) {
                // Do nothing, we want to display the invalid JSON as a string, rather
                // than present an error.
            }
        }

        // When the query and variables string is edited, update the URL bar so
        // that it can be easily shared
        function onEditQuery(newQuery) {
            parameters.query = newQuery;
            updateURL();
        }

        function onEditVariables(newVariables) {
            parameters.variables = newVariables;
            updateURL();
        }

        function onEditOperationName(newOperationName) {
            parameters.operationName = newOperationName;
            updateURL();
        }

        function updateURL() {
            var newSearch = '?' + Object.keys(parameters).filter(function (key) {
                        return Boolean(parameters[key]);
                    }).map(function (key) {
                        return encodeURIComponent(key) + '=' +
                            encodeURIComponent(parameters[key]);
                    }).join('&');
            history.replaceState(null, null, newSearch);
        }

        function getCookie(name) {
            var value = "; " + document.cookie;
            var parts = value.split("; " + name + "=");
            if (parts.length == 2) return parts.pop().split(";").shift();
        }

        // Defines a GraphQL fetcher using the fetch API.
        function graphQLFetcher(graphQLParams) {
            var headers = {
                'Accept':       'application/json',
                'Content-Type': 'application/json',
            };

            var cookie = getCookie('XSRF-TOKEN');

            if (cookie != undefined) {
                headers['X-XSRF-TOKEN'] = cookie;
            }

            return fetch("<?=$graphQLUrl;?>", {
                method:      'post',
                headers:     headers,
                body:        JSON.stringify(graphQLParams),
                credentials: 'include'
            }).then(function (response) {
                return response.text();
            }).then(function (responseBody) {
                try {
                    return JSON.parse(responseBody);
                } catch (error) {
                    return responseBody;
                }
            });
        }

        // Render <GraphiQL /> into the body.
        ReactDOM.render(
                React.createElement(GraphiQL, {
                    fetcher:             graphQLFetcher,
                    query:               parameters.query,
                    variables:           parameters.variables,
                    operationName:       parameters.operationName,
                    onEditQuery:         onEditQuery,
                    onEditVariables:     onEditVariables,
                    onEditOperationName: onEditOperationName
                }),
                document.getElementById('graphiql')
        );
    </script>
[% end %]