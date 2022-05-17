package com.example.bookstore;

import android.content.Context;
import android.os.Bundle;
import android.util.Log;
import android.view.Gravity;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TableLayout;
import android.widget.TableRow;
import android.widget.TextView;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

public class SearchActivity extends AppCompatActivity {
    Button search;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_search);
        RequestQueue requestQueue = Volley.newRequestQueue(this);

        search = findViewById(R.id.search_btn);
        search.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                EditText editTitle = findViewById(R.id.editText_title);
                String title = editTitle.getText().toString();

                EditText editAuthor = findViewById(R.id.editText_author);
                String author = editAuthor.getText().toString();

                Log.d("Search", "Input data: title= " + title + ", author= " + author);

                StringRequest stringRequest = new StringRequest(Request.Method.POST,
                        getString(R.string.url) + "search.php",
                        new Response.Listener<String>() {
                            @Override
                            public void onResponse(String response) {
                                Context context = SearchActivity.this;
                                TableLayout resultTable = (TableLayout) findViewById(R.id.resultTable);

                                // remove the previous result rows
                                resultTable.removeViews(1, resultTable.getChildCount() - 1);

                                Log.d("Search", "response: " + response);

                                // get search result
                                JSONArray results = null;
                                try {
                                    results = new JSONArray(response);
                                    // parse the search result data
                                    for (int i = 0; i < results.length(); i++) {
                                        JSONObject searchResult = results.getJSONObject(i);
                                        TableRow row = new TableRow(context);
                                        TableRow.LayoutParams layoutParams = new TableRow.LayoutParams(TableRow.LayoutParams.WRAP_CONTENT);
                                        row.setLayoutParams(layoutParams);

                                        TextView title = new TextView(context);
                                        TableRow.LayoutParams l1 = new TableRow.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.MATCH_PARENT, 2);
                                        TextView author = new TextView(context);
                                        TableRow.LayoutParams l2 = new TableRow.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.MATCH_PARENT, 1);

                                        // title
                                        title.setText(searchResult.getString("title"));
                                        title.setLayoutParams(l1);
                                        title.setGravity(Gravity.CENTER);
                                        title.setBackground(getDrawable(R.drawable.border));
                                        // author
                                        author.setText(searchResult.getString("author"));
                                        author.setLayoutParams(l2);
                                        author.setGravity(Gravity.CENTER);
                                        author.setBackground(getDrawable(R.drawable.border));

                                        // add data to row
                                        row.addView(title);
                                        row.addView(author);

                                        // add row to table
                                        resultTable.addView(row, i + 1);
                                    }
                                } catch (JSONException e) {
                                    e.printStackTrace();
                                }
                            }
                        }, new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {

                    }
                }) {
                    @Nullable
                    @Override
                    protected Map<String, String> getParams() throws AuthFailureError {
                        Map<String, String> map = new HashMap<String, String>();
                        map.put("title", title);
                        map.put("author", author);
                        return map;
                    }
                };

                requestQueue.add(stringRequest);
            }
        });
    }
}